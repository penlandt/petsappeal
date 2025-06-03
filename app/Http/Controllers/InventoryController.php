<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\ProductInventory;
use App\Models\Product;

class InventoryController extends Controller
{
    public function showCountSheet($locationId)
    {
        $location = Location::findOrFail($locationId);
        $companyId = auth()->user()->company_id;

        // All products for this company
        $products = Product::where('company_id', $companyId)->get();

        // Inventory records for this location, keyed by product_id
        $inventoryByProduct = ProductInventory::where('location_id', $locationId)
            ->get()
            ->keyBy('product_id');

        // Build unified inventory list with default quantity 0
        $inventories = $products->map(function ($product) use ($inventoryByProduct) {
            $inventory = $inventoryByProduct->get($product->id);

            return (object) [
                'product' => $product,
                'quantity' => $inventory ? $inventory->quantity : 0,
                'inventory_id' => $inventory ? $inventory->id : null,
            ];
        });

        // Custom sort logic
        $inventories = $inventories->sortBy(function ($item) {
            $product = $item->product;
            return [
                is_null($product->sku) ? 1 : 0,             // SKU products first
                is_null($product->sku) ? (
                    is_null($product->upc) ? 2 : 1          // Then UPC if no SKU
                ) : 0,
                strtolower($product->sku ?? $product->upc ?? $product->name),
            ];
        })->values(); // Re-index the collection

        return view('inventory.count-sheet', compact('location', 'inventories'));
    }

    public function reconcile(Request $request)
    {
        $validated = $request->validate([
            'location_id' => 'required|integer|exists:locations,id',
            'counts' => 'required|array',
            'counts.*' => 'nullable|integer|min:0',
        ]);

        $locationId = $validated['location_id'];
        $counts = $validated['counts'];

        foreach ($counts as $inventoryKey => $actualCount) {
            if ($actualCount === null) {
                continue; // Skip empty fields
            }

            if (str_starts_with($inventoryKey, 'new_')) {
                $productId = (int) str_replace('new_', '', $inventoryKey);

                ProductInventory::updateOrCreate(
                    ['product_id' => $productId, 'location_id' => $locationId],
                    ['quantity' => $actualCount]
                );
            } else {
                $inventory = ProductInventory::where('id', $inventoryKey)
                    ->where('location_id', $locationId)
                    ->first();

                if ($inventory) {
                    $inventory->quantity = $actualCount;
                    $inventory->save();
                }
            }
        }

        return redirect()->back()->with('success', 'Inventory successfully reconciled.');
    }
}
