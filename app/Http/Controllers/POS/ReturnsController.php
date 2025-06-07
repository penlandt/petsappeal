<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class ReturnsController extends Controller
{
    public function index()
{
    // For now, just return a placeholder view
    return view('pos.returns.index');
}

public function getClientSales(Request $request, $clientId)
{
    $user = auth()->user();

    // Validate client belongs to user's company
    $client = \App\Models\Client::where('id', $clientId)
        ->where('company_id', $user->company_id)
        ->firstOrFail();

    // Optional filters (e.g., date range)
    $startDate = $request->query('start_date');
    $endDate = $request->query('end_date');

    $query = \App\Models\POS\Sale::with(['items'])
        ->where('client_id', $clientId)
        ->where('company_id', $user->company_id);

    if ($startDate) {
        $query->whereDate('created_at', '>=', $startDate);
    }
    if ($endDate) {
        $query->whereDate('created_at', '<=', $endDate);
    }

    $sales = $query->orderBy('created_at', 'desc')->get();

    // Format sales and items to include returnable quantities
    $result = $sales->map(function ($sale) {
        $items = $sale->items->map(function ($item) {
            $returnableQty = $item->quantity - $item->returned_quantity;
            if ($returnableQty <= 0) {
                return null; // Filter this item out
            }
    
            return [
                'sale_item_id' => $item->id,
                'product_id' => $item->product_id,
                'name' => $item->name,
                'quantity' => $item->quantity,
                'returned_quantity' => $item->returned_quantity,
                'returnable_quantity' => $returnableQty,
                'price' => $item->price,
            ];
        })->filter();
    
        if ($items->isEmpty()) {
            return null; // Filter out this sale entirely
        }
    
        return [
            'id' => $sale->id,
            'created_at' => $sale->created_at->toDateString(),
            'total' => $sale->total,
            'items' => $items->values()
        ];
    })->filter()->values();

    return response()->json($result);
}

public function processReturn(Request $request)
{
    Log::info('processReturn was called.', $request->all());

    $request->validate([
        'sale_id'       => 'required|integer|exists:pos_sales,id',
        'refund_method' => 'required|string|max:50',
        'items'         => 'required|array|min:1',
        'items.*.sale_item_id' => 'required|integer|exists:pos_sale_items,id',
        'items.*.product_id'   => 'required|integer|exists:products,id',
        'items.*.quantity'     => 'required|integer|min:1',
    ]);

    $saleId = $request->input('sale_id');
    $refundMethod = $request->input('refund_method');
    $items = $request->input('items');
    $locationId = auth()->user()->selected_location_id;
    $companyId = auth()->user()->company_id;

    $sale = \App\Models\POS\Sale::with('items')
        ->where('id', $saleId)
        ->where('company_id', $companyId)
        ->firstOrFail();

    $clientId = $sale->client_id;
    $pointValue = \App\Models\LoyaltyProgram::where('company_id', $companyId)->value('point_value') ?? 0;
    $totalRefund = 0;
    $totalPointsRedeemed = 0;

    DB::beginTransaction();

    try {
        // Create the parent return transaction
        $return = \App\Models\POS\ReturnTransaction::create([
            'sale_id'       => $saleId,
            'client_id'     => $clientId,
            'refund_method' => $refundMethod,
            'location_id'   => $locationId,
            'refund_amount' => 0, // will update after line item loop
            'points_redeemed' => 0,
        ]);

        foreach ($items as $item) {
            $saleItemId = $item['sale_item_id'];
            $productId = $item['product_id'];
            $quantity = $item['quantity'];

            $saleItem = $sale->items->where('id', $saleItemId)->first();

            if (!$saleItem) {
                throw new \Exception("Sale item #$saleItemId not found in sale #$saleId.");
            }

            $returnableQty = $saleItem->quantity - $saleItem->returned_quantity;
            if ($quantity > $returnableQty) {
                throw new \Exception("Return quantity for item #$saleItemId exceeds available.");
            }

            // Update inventory
            $product = \App\Models\Product::findOrFail($productId);
            $product->quantity += $quantity;
            $product->save();

            // Calculate reversals
            $earnedToReverse = round($saleItem->points_earned * ($quantity / $saleItem->quantity), 2);
            $redeemedToRestore = round($saleItem->points_redeemed * ($quantity / $saleItem->quantity), 2);
            $taxRefund = round($saleItem->tax_amount * ($quantity / $saleItem->quantity), 2);
            $pointsValue = $redeemedToRestore * $pointValue;

            // Reverse earned points
            if ($clientId && $earnedToReverse > 0) {
                \App\Models\LoyaltyPointTransaction::create([
                    'client_id'   => $clientId,
                    'company_id'  => $companyId,
                    'points'      => -$earnedToReverse,
                    'type'        => 'earn',
                    'description' => 'Return reversal of earned points for Sale Item #' . $saleItemId,
                    'created_at'  => now(),
                ]);
            }

            // Restore redeemed points
            if ($clientId && $redeemedToRestore > 0) {
                \App\Models\LoyaltyPointTransaction::create([
                    'client_id'   => $clientId,
                    'company_id'  => $companyId,
                    'points'      => $redeemedToRestore,
                    'type'        => 'earn',
                    'description' => 'Return reversal of redeemed points for Sale Item #' . $saleItemId,
                    'created_at'  => now(),
                ]);
            }

            $saleItem->returned_quantity += $quantity;
            $saleItem->save();

            $lineSubtotal = $saleItem->price * $quantity;
            $lineTotal = $lineSubtotal + $taxRefund;
            $refundAmount = round($lineTotal - $pointsValue, 2);

            // Save return item
            \App\Models\POS\ReturnItem::create([
                'return_id'    => $return->id,
                'sale_item_id' => $saleItemId,
                'product_id'   => $productId,
                'quantity'     => $quantity,
                'price'        => $saleItem->price,
                'tax'          => $taxRefund,
                'line_total'   => $lineTotal,
            ]);

            $totalRefund += $refundAmount;
            $totalPointsRedeemed += $redeemedToRestore;
        }

        // Update parent return total
        $return->update([
            'refund_amount' => $totalRefund,
            'points_redeemed' => $totalPointsRedeemed,
        ]);

        DB::commit();
        return response()->json([
            'success' => true,
            'return_id' => $return->id,
            'refund_amount' => round($totalRefund, 2),
            'refund_method' => $refundMethod,
        ]);

    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('Multi-item return failed', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'error' => 'Return failed: ' . $e->getMessage()], 500);
    }
}

public function show($id)
{
    $user = auth()->user();

    $return = ReturnTransaction::with(['items.product', 'client', 'location'])
        ->where('id', $id)
        ->where('company_id', $user->company_id)
        ->firstOrFail();

    return view('pos.return-receipt', ['return' => $return]);
}



public function showReceipt($returnId)
{
    $return = \App\Models\POS\ReturnTransaction::with(['items.product'])->findOrFail($returnId);

    return view('pos.return-receipt', compact('return'));
}

}
