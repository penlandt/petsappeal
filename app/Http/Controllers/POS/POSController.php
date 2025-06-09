<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Modules\Invoices\Invoice;
use App\Models\ProductInventory;
use App\Models\LoyaltyPointTransaction;
use Illuminate\Support\Facades\Log;

class POSController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $companyId = $user->company_id;
        $selectedLocationId = $user->selected_location_id;

        $clients = \App\Models\Client::where('company_id', $companyId)
            ->orderBy('last_name')
            ->get();

        $locations = \App\Models\Location::where('company_id', $companyId)
            ->where('inactive', false)
            ->orderBy('name')
            ->get();

        $productTaxRate = 0;

        if ($selectedLocationId) {
            $location = \App\Models\Location::find($selectedLocationId);
            if ($location) {
                $productTaxRate = $location->product_tax_rate ?? 0;
            }
        }

        return view('pos.index', [
            'clients' => $clients,
            'locations' => $locations,
            'productTaxRate' => $productTaxRate,
        ]);
    }

    public function setLocation(Request $request)
    {
        $request->validate([
            'location_id' => 'required|exists:locations,id',
        ]);

        session(['selected_location_id' => $request->location_id]);

        return redirect()->route('pos.index');
    }

    public function checkout(Request $request)
{
    $user = auth()->user();
    $companyId = $user->company_id;
    $locationId = $user->selected_location_id;

    $validated = $request->validate([
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'nullable|integer|exists:products,id',
        'items.*.name' => 'required|string',
        'items.*.price' => 'required|numeric',
        'items.*.quantity' => 'required|numeric',
        'items.*.invoice_id' => 'nullable|integer',
        'payments' => 'required|array|min:1',
        'payments.*.method' => 'required|string',
        'payments.*.amount' => 'required|numeric',
        'payments.*.reference_number' => 'nullable|string',
        'client_id' => 'nullable|exists:clients,id',
        'redeem_points' => 'nullable|boolean',
        'total_due' => 'required|numeric',
    ]);

    $clientTotalDue = round($validated['total_due'], 2);

    $items = $validated['items'];
    $payments = $validated['payments'];
    $clientId = $validated['client_id'] ?? null;
    $redeemPoints = filter_var($validated['redeem_points'] ?? false, FILTER_VALIDATE_BOOLEAN);
    $location = \App\Models\Location::findOrFail($locationId);
    
    $items = collect($items)->map(function ($item) use ($location) {
        $lineTotal = $item['price'] * $item['quantity'];
        $lineTax = 0;
        $isTaxable = false;
    
        // Skip tax calculation entirely for invoice items
        if (!empty($item['invoice_id'])) {
            return array_merge($item, [
                'line_total' => $lineTotal,
                'tax_amount' => $lineTax,
                'taxable' => $isTaxable ?? false,
            ]);
        }
    
        // Normal product – check if taxable
        if (!empty($item['product_id'])) {
            $product = \App\Models\Product::find($item['product_id']);
            $isTaxable = $product && (int) $product->taxable === 1;
    
            if ($isTaxable && $location->product_tax_rate > 0) {
                $lineTax = round($lineTotal * ($location->product_tax_rate / 100), 2);
            }
        }
    
        return array_merge($item, [
            'line_total' => $lineTotal,
            'tax_amount' => $lineTax,
            'taxable' => $isTaxable ?? false,
        ]);
    })->toArray();
    
    
    $subtotal = array_sum(array_column($items, 'line_total'));
    $tax = array_sum(array_column($items, 'tax_amount'));
    $total = $subtotal + $tax;
    $originalTotal = $total;
    

    $pointsRedeemed = 0;
    $pointsValue = 0;
    $program = null;

    if ($clientId && $redeemPoints) {
        $company = $user->company;
        $program = $company->loyaltyProgram;

        if ($program) {
            $earned = \App\Models\LoyaltyPointTransaction::where('client_id', $clientId)
                ->where('company_id', $company->id)
                ->where('type', 'earn')->sum('points');

            $redeemed = \App\Models\LoyaltyPointTransaction::where('client_id', $clientId)
                ->where('company_id', $company->id)
                ->where('type', 'redeem')->sum('points');

            $available = $earned - $redeemed;

            $maxPercent = $program->max_discount_percent ?? 0;
            $maxDiscount = round($subtotal * ($maxPercent / 100), 2);
            $pointValue = $program->point_value;
            $maxPointsToUse = floor($maxDiscount / $pointValue);

            $pointsToUse = min($available, $maxPointsToUse);
            $pointsValue = round($pointsToUse * $pointValue, 2);
            $pointsRedeemed = $pointsToUse;

            $total = round($total - $pointsValue, 2);
        }
    }

    $amountPaid = collect($payments)->sum('amount');
    $changeOwed = max(0, $amountPaid - $clientTotalDue);

    DB::beginTransaction();

    try {
        // Final totals after all discounts
        $subtotal = array_sum(array_column($items, 'line_total'));
        $tax = array_sum(array_column($items, 'tax_amount'));
        $total = $subtotal + $tax - $pointsValue;
    
        $sale = \App\Models\POS\Sale::create([
            'company_id' => $companyId,
            'location_id' => $locationId,
            'client_id' => $clientId,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
        ]);
    
        foreach ($items as $item) {
            $lineTotal = $item['line_total'];
            $lineTax = $item['tax_amount'];
    
            $itemPointsEarned = 0;
            $itemPointsRedeemed = 0;
    
            if ($clientId && $program) {
                if ($redeemPoints && $pointsValue > 0 && $subtotal > 0) {
                    $itemDiscountShare = $lineTotal / $subtotal;
                    $itemDiscountValue = $pointsValue * $itemDiscountShare;
                    $itemPointsRedeemed = round($itemDiscountValue / $program->point_value, 2);
                }
    
                $earnableAmount = $lineTotal;
                if ($redeemPoints) {
                    $earnableAmount = max(0, $lineTotal - ($itemPointsRedeemed * $program->point_value));
                }
    
                $itemPointsEarned = round($earnableAmount * $program->points_per_dollar, 2);
            }
    
            $sale->items()->create([
                'product_id' => $item['product_id'] ?? null,
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'line_total' => $lineTotal,
                'tax_amount' => $lineTax,
                'points_earned' => $itemPointsEarned,
                'points_redeemed' => $itemPointsRedeemed,
            ]);
    
            if (!empty($item['invoice_id'])) {
                $invoice = \App\Models\Modules\Invoices\Invoice::find($item['invoice_id']);
                if ($invoice && $invoice->status !== 'Paid') {
                    $invoice->status = 'Paid';
                    $invoice->amount_paid = $invoice->total_amount;
                    $invoice->save();
            
                    // 📧 Auto-send invoice email if allowed
                    $emailSettings = \App\Models\EmailSetting::where('company_id', $companyId)->first();
                    $client = $invoice->client;
            
                    if (
                        $emailSettings &&
                        $emailSettings->send_invoices_automatically &&
                        $client &&
                        filter_var($client->email, FILTER_VALIDATE_EMAIL)
                    ) {
                        try {
                            \App\Services\CompanyMailer::to($client->email)->send(new \App\Mail\InvoiceEmail($invoice));
                            \Log::info('📧 Invoice email sent to client', ['email' => $client->email]);
                        } catch (\Throwable $emailEx) {
                            \Log::error('❌ Failed to send invoice email', ['error' => $emailEx->getMessage()]);
                        }
                    }
                }
            }
            
    
            if (!empty($item['product_id'])) {
                $inventory = \App\Models\ProductInventory::where('product_id', $item['product_id'])
                    ->where('location_id', $locationId)
                    ->first();
    
                if ($inventory) {
                    $inventory->quantity -= $item['quantity'];
                    $inventory->save();
                }
            }
        }
    
        foreach ($payments as $payment) {
            $sale->payments()->create([
                'method' => $payment['method'],
                'amount' => $payment['amount'],
                'reference_number' => $payment['reference_number'] ?? null,
            ]);
        }
    
        if ($clientId && $program) {
            $earnableAmount = $subtotal;
            if ($redeemPoints) {
                $earnableAmount = max(0, $earnableAmount - $pointsValue);
            }
    
            $pointsEarned = round($earnableAmount * $program->points_per_dollar, 2);
    
            // Earned points
            \App\Models\LoyaltyPointTransaction::create([
                'client_id' => $clientId,
                'company_id' => $company->id,
                'pos_sale_id' => $sale->id,
                'points' => $pointsEarned,
                'type' => 'earn',
                'description' => 'Points earned for Sale #' . $sale->id,
                'created_at' => now(),
            ]);
    
            // Redeemed points
            if ($redeemPoints && $pointsRedeemed > 0) {
                \App\Models\LoyaltyPointTransaction::create([
                    'client_id' => $clientId,
                    'company_id' => $company->id,
                    'pos_sale_id' => $sale->id,
                    'points' => $pointsRedeemed,
                    'type' => 'redeem',
                    'description' => 'Points redeemed for Sale #' . $sale->id,
                    'created_at' => now(),
                ]);
            }
        }
    
        DB::commit();
    
        // Send email receipt if conditions are met
        $emailSettings = \App\Models\EmailSetting::where('company_id', $companyId)->first();

        if (
            $emailSettings &&
            $emailSettings->send_receipts_automatically &&
            $clientId
        ) {
            $client = \App\Models\Client::find($clientId);
            if ($client && filter_var($client->email, FILTER_VALIDATE_EMAIL)) {
                try {
                    \App\Services\CompanyMailer::to($client->email)->send(new \App\Mail\ReceiptEmail($sale));
                    Log::info('📧 Receipt email sent to client', ['email' => $client->email]);
                } catch (\Throwable $emailException) {
                    Log::error('❌ Failed to send receipt email', ['error' => $emailException->getMessage()]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'sale_id' => $sale->id,
            'change_owed' => number_format($changeOwed, 2)
        ]);
    } catch (\Throwable $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'error' => 'Checkout failed: ' . $e->getMessage(),
        ], 500);
    }
    
}

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'upc' => 'nullable|string|max:255',
            'sku' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'inactive' => 'nullable|boolean',
            'client_id' => 'nullable|exists:clients,id',
        ]);

        $user = auth()->user();

        $product = new \App\Models\Product();
        $product->company_id = $user->company_id;
        $product->name = $validated['name'];
        $product->price = $validated['price'];
        $product->cost = $validated['cost'];
        $product->quantity = $validated['quantity'];
        $product->upc = $validated['upc'];
        $product->sku = $validated['sku'];
        $product->description = $validated['description'];
        $product->inactive = $validated['inactive'] ?? 0;
        $product->save();

        return response()->json(['success' => true, 'product_id' => $product->id]);
    }

    public function getUnpaidInvoices($clientId)
    {
        $user = auth()->user();
        $locationId = $user->selected_location_id;

        $invoices = \App\Models\Modules\Invoices\Invoice::with('items')
            ->where('location_id', $locationId)
            ->where('client_id', $clientId)
            ->where('status', 'Unpaid')
            ->get();

        $cartItems = [];

        foreach ($invoices as $invoice) {
            $type = 'Invoice';
            if ($invoice->items->contains('item_type', 'App\\Models\\Modules\\Appointments\\Appointment')) {
                $type = 'Grooming Invoice';
            } elseif ($invoice->items->contains('item_type', 'App\\Models\\Modules\\Boarding\\BoardingReservation')) {
                $type = 'Boarding Invoice';
            }

            $cartItems[] = [
                'id' => 'invoice-' . $invoice->id,
                'name' => "{$type} #{$invoice->id}",
                'price' => $invoice->total_amount,
                'quantity' => 1,
                'source' => 'invoice',
                'invoice_id' => $invoice->id,
                'taxable' => false,
            ];
            
        }

        return response()->json($cartItems);
    }

public function getClientPoints($clientId)
{
    $client = \App\Models\Client::findOrFail($clientId);
    $user = auth()->user();

    // Only allow access if the client belongs to the same company
    if ($client->company_id !== $user->company_id) {
        abort(403, 'Unauthorized');
    }

    $points = round($client->availableLoyaltyPoints(), 2);
    $pointValue = $client->company->loyaltyProgram->point_value ?? 0;  // Default to 0 if no loyalty program
    $maxDiscountPercent = $client->company->loyaltyProgram->max_discount_percent ?? 0;

    return response()->json([
        'points' => $points,
        'point_value' => $pointValue,
        'max_discount_percent' => $maxDiscountPercent,
    ]);
}


}
