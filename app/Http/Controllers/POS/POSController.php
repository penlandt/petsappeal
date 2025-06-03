<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Modules\Invoices\Invoice;
use App\Models\ProductInventory;

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
            'items.*.product_id' => 'nullable|integer',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|numeric',
            'items.*.invoice_id' => 'nullable|integer',
            'payments' => 'required|array|min:1',
            'payments.*.method' => 'required|string',
            'payments.*.amount' => 'required|numeric',
            'payments.*.reference_number' => 'nullable|string',
            'client_id' => 'nullable|exists:clients,id',
        ]);

        $items = $validated['items'];
        \Log::info('Cart items received:', $items);

        $payments = $validated['payments'];
        $clientId = $validated['client_id'] ?? null;

        $location = \App\Models\Location::findOrFail($locationId);

        $taxableSubtotal = 0;
        $nonTaxableSubtotal = 0;

        foreach ($items as $item) {
            $lineTotal = $item['price'] * $item['quantity'];

            if (!empty($item['invoice_id'])) {
                $nonTaxableSubtotal += $lineTotal;
            } else {
                $taxableSubtotal += $lineTotal;
            }
        }

        $tax = round($taxableSubtotal * ($location->product_tax_rate / 100), 2);
        $subtotal = $taxableSubtotal + $nonTaxableSubtotal;
        $total = $subtotal + $tax;

        $amountPaid = collect($payments)->sum('amount');
        $changeOwed = max(0, $amountPaid - $total);

        DB::beginTransaction();

        try {
            $sale = \App\Models\POS\Sale::create([
                'company_id' => $companyId,
                'location_id' => $locationId,
                'client_id' => $clientId,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
            ]);

            foreach ($items as $item) {
                $sale->items()->create([
                    'product_id' => $item['product_id'] ?? null,
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'line_total' => $item['price'] * $item['quantity'],
                ]);

                if (!empty($item['invoice_id'])) {
                    $invoice = Invoice::find($item['invoice_id']);
                    if ($invoice && $invoice->status !== 'Paid') {
                        $invoice->status = 'Paid';
                        $invoice->amount_paid = $invoice->total_amount;
                        $invoice->save();
                    }
                }

                // ✅ NEW: Adjust inventory at the sale location
                if (!empty($item['product_id'])) {
                    $inventory = ProductInventory::where('product_id', $item['product_id'])
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

            DB::commit();

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

        \Log::info("Fetching unpaid invoices", [
            'client_id' => $clientId,
            'location_id' => $locationId,
        ]);

        $invoices = \App\Models\Modules\Invoices\Invoice::with('items')
            ->where('location_id', $locationId)
            ->where('client_id', $clientId)
            ->where('status', 'Unpaid')
            ->get();

        \Log::info("Unpaid invoices found", [
            'count' => $invoices->count(),
            'ids' => $invoices->pluck('id'),
        ]);

        $cartItems = [];

        foreach ($invoices as $invoice) {
            $type = 'Invoice';
            if ($invoice->items->contains('item_type', 'App\\Models\\Modules\\Appointments\\Appointment')) {
                $type = 'Grooming Invoice';
            } elseif ($invoice->items->contains('item_type', 'App\\Models\\Modules\\Boarding\\BoardingReservation')) {
                $type = 'Boarding Invoice';
            }

            \Log::info("Preparing cart item", [
                'invoice_id' => $invoice->id,
                'label' => $type
            ]);

            $cartItems[] = [
                'id' => 'invoice-' . $invoice->id,
                'name' => "{$type} #{$invoice->id}",
                'price' => $invoice->total_amount,
                'quantity' => 1,
                'source' => 'invoice',
                'invoice_id' => $invoice->id,
            ];
        }

        return response()->json($cartItems);
    }
}
