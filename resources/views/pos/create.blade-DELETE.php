<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Add New Product
            </h2>
            <x-pos-secondary-menu />
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-3xl mx-auto">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <form method="POST" action="{{ route('pos.products.store') }}">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input id="name" name="name" type="text" required autofocus
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white"
                           value="{{ old('name') }}">
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="upc" class="block text-sm font-medium text-gray-700 dark:text-gray-300">UPC (Optional)</label>
                    <input id="upc" name="upc" type="text"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white"
                           value="{{ old('upc') }}">
                    @error('upc')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label>
                    <input id="price" name="price" type="number" step="0.01" min="0" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white"
                           value="{{ old('price') }}">
                    @error('price')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="cost" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cost</label>
                    <input id="cost" name="cost" type="number" step="0.01" min="0" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white"
                           value="{{ old('cost') }}">
                    @error('cost')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="stock_quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stock Quantity</label>
                    <input id="stock_quantity" name="stock_quantity" type="number" min="0" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white"
                           value="{{ old('stock_quantity') }}">
                    @error('stock_quantity')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('pos.products') }}"
                       class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white font-semibold">
                        Save Product
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
