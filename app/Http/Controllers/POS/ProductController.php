<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
{
    $user = auth()->user();
    $companyId = $user->company_id;

    $showInactive = $request->query('show_inactive') === '1';

    $products = Product::where('company_id', $companyId)
        ->when(!$showInactive, fn($q) => $q->where('inactive', false))
        ->get();

    return view('modules.pos.products', compact('products', 'showInactive'));
}


    public function create()
    {
        return view('modules.pos.create-product');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'upc' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $user = auth()->user();
        $companyId = $user->company_id;

        $product = new Product();
        $product->company_id = $companyId;
        $product->name = $validated['name'];
        $product->upc = $validated['upc'] ?? null;
        $product->price = $validated['price'];
        $product->cost = $validated['cost'];
        $product->quantity = $validated['stock_quantity'];
        $product->save();

        return redirect()->route('pos.products')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $user = auth()->user();
        if ($product->company_id !== $user->company_id) {
            abort(403, 'Unauthorized access to this product.');
        }

        return view('modules.pos.edit-product', compact('product'));
    }

    public function update(Request $request, Product $product)
{
    $user = auth()->user();
    if ($product->company_id !== $user->company_id) {
        abort(403, 'Unauthorized access to this product.');
    }

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'upc' => 'nullable|string|max:255',
        'sku' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'cost' => 'required|numeric|min:0',
        'stock_quantity' => 'required|integer|min:0',
        'inactive' => 'nullable|boolean',
    ]);

    $product->name = $validated['name'];
    $product->upc = $validated['upc'] ?? null;
    $product->sku = $validated['sku'] ?? null;
    $product->description = $validated['description'] ?? null;
    $product->price = $validated['price'];
    $product->cost = $validated['cost'];
    $product->quantity = $validated['stock_quantity'];
    $product->inactive = $request->has('inactive') ? 1 : 0;

    $product->save();

    return redirect()->route('pos.products')->with('success', 'Product updated successfully.');
}


    public function getProductsJson(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;

        $search = $request->input('search', '');

        $query = Product::where('company_id', $companyId);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('upc', 'like', "%{$search}%");
            });
        }

        $products = $query->get();

        return response()->json($products);
    }

    public function apiProducts()
    {
        $user = auth()->user();
        $companyId = $user->company_id;

        $products = Product::where('company_id', $companyId)->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'cost' => (float) $product->cost,
                'stock_quantity' => $product->quantity,
                'upc' => $product->upc,
            ];
        });

        return response()->json($products);
    }

    public function search(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;

        $query = $request->input('q');

        $products = Product::where('company_id', $companyId)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('upc', 'like', "%{$query}%");
            })
            ->limit(50)
            ->get(['id', 'name', 'price', 'quantity'])
            ->map(function ($product) {
                $product->price = floatval($product->price);
                return $product;
            });

        return response()->json($products);
    }
}
