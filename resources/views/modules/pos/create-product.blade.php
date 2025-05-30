<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Add New Product
            </h2>
            <a href="{{ route('pos.products') }}" class="inline-block bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded">
                &larr; Back to Products
            </a>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-md mx-auto bg-white dark:bg-gray-800 rounded shadow p-6">
        <form method="POST" action="{{ route('pos.products.store') }}">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required
                    class="w-full border border-gray-300 rounded px-3 py-2 dark:bg-gray-700 dark:text-white">
                @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="upc" class="block text-gray-700 dark:text-gray-300 font-medium mb-1">UPC (optional)</label>
                <input id="upc" name="upc" type="text" value="{{ old('upc') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 dark:bg-gray-700 dark:text-white">
                @error('upc') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="price" class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Price</label>
                <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price') }}" required
                    class="w-full border border-gray-300 rounded px-3 py-2 dark:bg-gray-700 dark:text-white">
                @error('price') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="cost" class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Cost</label>
                <input id="cost" name="cost" type="number" step="0.01" min="0" value="{{ old('cost') }}" required
                    class="w-full border border-gray-300 rounded px-3 py-2 dark:bg-gray-700 dark:text-white">
                @error('cost') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="stock_quantity" class="block text-gray-700 dark:text-gray-300 font-medium mb-1">Stock Quantity</label>
                <input id="stock_quantity" name="stock_quantity" type="number" min="0" value="{{ old('stock_quantity', 0) }}" required
                    class="w-full border border-gray-300 rounded px-3 py-2 dark:bg-gray-700 dark:text-white">
                @error('stock_quantity') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                Save Product
            </button>
        </form>
    </div>
</x-app-layout>
