<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Edit Product
            </h2>
            <a href="{{ route('pos.products') }}"
               class="inline-block bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded">
                Back to Products
            </a>
        </div>
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8 max-w-md mx-auto bg-white dark:bg-gray-800 shadow-md rounded">
        <form method="POST" action="{{ route('pos.products.update', $product->id) }}">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                <input id="name" name="name" type="text" required
                       value="{{ old('name', $product->name) }}"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- UPC -->
            <div class="mb-4">
                <label for="upc" class="block text-sm font-medium text-gray-700 dark:text-gray-300">UPC</label>
                <input id="upc" name="upc" type="text"
                       value="{{ old('upc', $product->upc) }}"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                @error('upc')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- SKU -->
            <div class="mb-4">
                <label for="sku" class="block text-sm font-medium text-gray-700 dark:text-gray-300">SKU</label>
                <input id="sku" name="sku" type="text"
                       value="{{ old('sku', $product->sku) }}"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                @error('sku')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                <textarea id="description" name="description" rows="3"
                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">{{ old('description', $product->description) }}</textarea>
                @error('description')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Price -->
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label>
                <input id="price" name="price" type="number" step="0.01" min="0" required
                       value="{{ old('price', $product->price) }}"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                @error('price')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Cost -->
            <div class="mb-4">
                <label for="cost" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cost</label>
                <input id="cost" name="cost" type="number" step="0.01" min="0" required
                       value="{{ old('cost', $product->cost) }}"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                @error('cost')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Quantity -->
            <div class="mb-6">
                <label for="stock_quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stock Quantity</label>
                <input id="stock_quantity" name="stock_quantity" type="number" min="0" required
                       value="{{ old('stock_quantity', $product->stock_quantity ?? $product->quantity) }}"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                @error('stock_quantity')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Inactive -->
            <div class="mb-6">
                <label class="inline-flex items-center">
                    <input type="checkbox" id="inactive" name="inactive" value="1"
                        {{ old('inactive', $product->inactive) ? 'checked' : '' }}
                        class="accent-gray-600 dark:accent-blue-400">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Inactive</span>
                </label>
            </div>


            <!-- Submit -->
            <div class="flex justify-end">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
