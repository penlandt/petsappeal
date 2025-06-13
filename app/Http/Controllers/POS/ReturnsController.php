<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Refund;

class ReturnsController extends Controller
{
    public function index()
    {
        return view('pos.returns.index');
    }

    public function getClientSales(Request $request, $clientId)
    {
        $user = auth()->user();

        $client = \App\Models\Client::where('id', $clientId)
            ->where('company_id', $user->company_id)
            ->firstOrFail();

        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = \App\Models\POS\Sale::with(['items'])
            ->where('client_id', $clientId)
            ->where('location_id', $user->selected_location_id); // 🔄 FIXED

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $sales = $query->orderBy('created_at', 'desc')->get();

        $result = $sales->map(function ($sale) {
            $items = $sale->items->map(function ($item) {
                $returnableQty = $item->quantity - $item->returned_quantity;
                if ($returnableQty <= 0) return null;

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

            if ($items->isEmpty()) return null;

            return [
                'id' => $sale->id,
                'created_at' => $sale->created_at->toDateString(),
                'total' => $sale->total,
                'items' => $items->values()
            ];
        })->filter()->values();

        return response()->json($result);
    }

    public function getAnonymousSales()
    {
            \Log::info('getAnonymousSales() was hit.');
    
            $user = auth()->user();
    
            if (!$user || !$user->selected_location_id) {
                \Log::error('User or selected_location_id is missing', ['user' => $user]);
                return response()->json(['error' => 'User or location not found'], 403);
            }
    
            $sales = \App\Models\POS\Sale::with('items')
                ->whereNull('client_id')
                ->where('location_id', $user->selected_location_id) // 🔄 FIXED
                ->orderBy('created_at', 'desc')
                ->get();
    
            $result = $sales->map(function ($sale) {
                $items = $sale->items->map(function ($item) {
                    $returnableQty = $item->quantity - $item->returned_quantity;
                    if ($returnableQty <= 0) return null;
    
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
    
                if ($items->isEmpty()) return null;
    
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
            $location = auth()->user()->selectedLocation;
            $companyId = auth()->user()->company_id;
        
            $sale = \App\Models\POS\Sale::with(['items', 'payments'])
                ->where('id', $saleId)
                ->where('location_id', $location->id)
                ->firstOrFail();

            Log::debug('Payments for this sale:', ['payments' => $sale->payments]);

        
            $clientId = $sale->client_id;
            $pointValue = \App\Models\LoyaltyProgram::where('company_id', $companyId)->value('point_value') ?? 0;
            $totalRefund = 0;
            $totalPointsRedeemed = 0;
        
            DB::beginTransaction();
        
            try {
                $return = \App\Models\POS\ReturnTransaction::create([
                    'sale_id'       => $saleId,
                    'client_id'     => $clientId,
                    'refund_method' => $refundMethod,
                    'location_id'   => $location->id,
                    'refund_amount' => 0,
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
        
                    $product = \App\Models\Product::findOrFail($productId);
                    $product->quantity += $quantity;
                    $product->save();
        
                    $earnedToReverse = round($saleItem->points_earned * ($quantity / $saleItem->quantity), 2);
                    $redeemedToRestore = round($saleItem->points_redeemed * ($quantity / $saleItem->quantity), 2);
                    $taxRefund = round($saleItem->tax_amount * ($quantity / $saleItem->quantity), 2);
                    $pointsValue = $redeemedToRestore * $pointValue;
        
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
        
                if (strtolower($refundMethod) === 'credit' && $location->stripe_account_id && $totalRefund > 0) {
                    Log::debug('All sale payments:', $sale->payments->toArray());

                    $stripePayment = $sale->payments->filter(function ($p) {
                        return strtolower($p->method) === 'credit';
                    })->first();

                    Log::debug('Matched Stripe payment:', ['stripePayment' => $stripePayment]);

        
                    if ($stripePayment && $stripePayment->stripe_charge_id) {
                        Stripe::setApiKey(config('services.stripe.secret'));
        
                        Log::info('Attempting Stripe refund', [
                            'charge_id' => $stripePayment->stripe_charge_id,
                            'amount_cents' => intval(round($totalRefund * 100)),
                            'amount_dollars' => $totalRefund,
                            'stripe_account' => $location->stripe_account_id,
                        ]);
        
                        Refund::create([
                            'charge' => $stripePayment->stripe_charge_id,
                            'amount' => intval(round($totalRefund * 100)),
                        ], [
                            'stripe_account' => $location->stripe_account_id,
                        ]);
                    } else {
                        Log::warning('Stripe payment or charge ID missing; skipping refund.', [
                            'stripePayment' => $stripePayment,
                        ]);
                    }
                }
        
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
        
    }