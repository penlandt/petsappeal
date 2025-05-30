<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $companyId = $user->company_id;

        // Fetch products belonging to this user's company
        $products = Product::where('company_id', $companyId)->get();

        return view('modules.pos.products', compact('products'));
    }

    // Show the form to create a new product
    public function create()
    {
        return view('modules.pos.create-product');
    }

    // Store a new product in database
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
        $product->stock_quantity = $validated['stock_quantity'];
        $product->save();

        return redirect()->route('pos.products')->with('success', 'Product created successfully.');
    }

    // Show the form to edit an existing product
    public function edit(Product $product)
    {
        $user = auth()->user();
        if ($product->company_id !== $user->company_id) {
            abort(403, 'Unauthorized access to this product.');
        }

        return view('modules.pos.edit-product', compact('product'));
    }

    // Update an existing product in the database
    public function update(Request $request, Product $product)
    {
        $user = auth()->user();
        if ($product->company_id !== $user->company_id) {
            abort(403, 'Unauthorized access to this product.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'upc' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $product->name = $validated['name'];
        $product->upc = $validated['upc'] ?? null;
        $product->price = $validated['price'];
        $product->cost = $validated['cost'];
        $product->stock_quantity = $validated['stock_quantity'];
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
        $query->where(function($q) use ($search) {
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
            'price' => (float) $product->price,    // cast price to float
            'cost' => (float) $product->cost,      // cast cost to float
            'stock_quantity' => $product->stock_quantity,
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
