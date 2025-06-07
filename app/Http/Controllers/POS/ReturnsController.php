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
        'sale_id'        => 'required|integer|exists:pos_sales,id',
        'sale_item_id'   => 'required|integer|exists:pos_sale_items,id',
        'product_id'     => 'required|integer|exists:products,id',
        'quantity'       => 'required|integer|min:1',
        'refund_method'  => 'required|string|max:50',
    ]);

    $saleId = $request->input('sale_id');
    $saleItemId = $request->input('sale_item_id');
    $productId = $request->input('product_id');
    $quantity = $request->input('quantity');
    $refundMethod = $request->input('refund_method');
    $locationId = auth()->user()->selected_location_id;
    $companyId = auth()->user()->company_id;

    $sale = \App\Models\POS\Sale::with('items')
        ->where('id', $saleId)
        ->where('company_id', $companyId)
        ->firstOrFail();

    $clientId = $sale->client_id;
    $saleItem = $sale->items->where('id', $saleItemId)->first();

    if (!$saleItem) {
        return response()->json(['success' => false, 'error' => 'Sale item not found in this sale.'], 404);
    }

    $returnableQty = $saleItem->quantity - $saleItem->returned_quantity;
    if ($quantity > $returnableQty) {
        return response()->json(['success' => false, 'error' => 'Return quantity exceeds what was sold.'], 400);
    }

    DB::beginTransaction();

    try {
        // Increase product inventory
        $product = \App\Models\Product::findOrFail($productId);
        $product->quantity += $quantity;
        $product->save();

        // Calculate proportional reversals
        $pointsEarnedToReverse = round($saleItem->points_earned * ($quantity / $saleItem->quantity), 2);
        $pointsRedeemedToReverse = round($saleItem->points_redeemed * ($quantity / $saleItem->quantity), 2);
        $taxRefund = round($saleItem->tax_amount * ($quantity / $saleItem->quantity), 2);

        // Reverse earned points
        if ($clientId && $pointsEarnedToReverse > 0) {
            \App\Models\LoyaltyPointTransaction::create([
                'client_id'   => $clientId,
                'company_id'  => $companyId,
                'points'      => -$pointsEarnedToReverse,
                'type'        => 'earn',
                'description' => 'Return reversal of earned points for Sale Item #' . $saleItem->id,
                'created_at'  => now(),
            ]);
        }

        // Restore redeemed points
        if ($clientId && $pointsRedeemedToReverse > 0) {
            \App\Models\LoyaltyPointTransaction::create([
                'client_id'   => $clientId,
                'company_id'  => $companyId,
                'points'      => $pointsRedeemedToReverse,
                'type'        => 'earn',
                'description' => 'Return reversal of redeemed points for Sale Item #' . $saleItem->id,
                'created_at'  => now(),
            ]);
        }

        // Update returned quantity
        $saleItem->returned_quantity += $quantity;
        $saleItem->save();

        // ✅ Get point value from the correct table
        $pointValue = \App\Models\LoyaltyProgram::where('company_id', $companyId)->value('point_value') ?? 0;
        $pointsValue = $pointsRedeemedToReverse * $pointValue;

        $refundAmount = round(($saleItem->price * $quantity) + $taxRefund - $pointsValue, 2);

        // Record the return
        \DB::table('pos_returns')->insert([
            'sale_id'       => $saleId,
            'client_id'     => $clientId,
            'product_id'    => $productId,
            'quantity'      => $quantity,
            'price'         => $saleItem->price,
            'tax_amount'    => $taxRefund,
            'refund_amount' => $refundAmount,
            'points_redeemed' => $pointsRedeemedToReverse,
            'refund_method' => $refundMethod,
            'location_id'   => $locationId,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);


        DB::commit();
        return response()->json(['success' => true]);

    } catch (\Throwable $e) {
        DB::rollBack();
        return response()->json(['success' => false, 'error' => 'Return failed: ' . $e->getMessage()], 500);
    }
}

public function show($id)
{
    $return = \DB::table('pos_returns')
        ->where('id', $id)
        ->where('company_id', auth()->user()->company_id)
        ->first();

    if (!$return) {
        abort(404);
    }

    // Load related data
    $return->product = \App\Models\Product::find($return->product_id);
    $return->client = \App\Models\Client::find($return->client_id);
    $return->location = \App\Models\Location::find($return->location_id);

    // Wrap in object so we can attach 'items' collection for consistency
    $return = collect($return)->toBase();

    $return->items = collect([
        (object)[
            'product' => $return->product,
            'quantity' => $return->quantity,
            'price' => $return->price,
            'tax_amount' => $return->tax_amount ?? 0,
        ]
    ]);

    return view('pos.returns.show', ['return' => $return]);
}

}
