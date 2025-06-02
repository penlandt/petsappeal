<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        if ($selectedLocationId) {
            $location = \App\Models\Location::findOrFail($selectedLocationId);
            $productTaxRate = $location->product_tax_rate;

            return view('pos.index', [
                'productTaxRate' => $productTaxRate,
                'clients' => $clients,
            ]);
        } else {
            $locations = \App\Models\Location::where('company_id', $companyId)
                ->where('inactive', false)
                ->orderBy('name')
                ->get();

            return view('pos.index', [
                'locations' => $locations,
                'clients' => $clients,
            ]);
        }
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

        \Log::info('Raw request client_id:', ['client_id' => $request->input('client_id')]);


        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|integer',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|numeric',
            'payments' => 'required|array|min:1',
            'payments.*.method' => 'required|string',
            'payments.*.amount' => 'required|numeric',
            'payments.*.reference_number' => 'nullable|string',
            'client_id' => 'nullable|exists:clients,id',
        ]);

        $items = $validated['items'];
        $payments = $validated['payments'];
        $clientId = $validated['client_id'] ?? null;
        
        $subtotal = collect($items)->sum(fn($item) => $item['price'] * $item['quantity']);
        $location = \App\Models\Location::findOrFail($locationId);
        $tax = round($subtotal * ($location->product_tax_rate / 100), 2);
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

    
}
