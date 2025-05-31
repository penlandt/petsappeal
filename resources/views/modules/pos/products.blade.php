<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Manage Products
            </h2>

            <div class="flex items-center space-x-4">
                @include('pos.partials.secondary-menu')

                <a href="{{ route('pos.products.create') }}"
                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                    + New Product
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <!-- Show Inactive Toggle -->
        <form method="GET" action="{{ route('pos.products') }}" class="mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="show_inactive" value="1" onchange="this.form.submit()"
                       {{ $showInactive ? 'checked' : '' }}
                       class="accent-gray-600 dark:accent-blue-400">
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Show Inactive</span>
            </label>
        </form>

        <!-- Filter Box -->
        <div class="mb-4">
            <input
                type="text"
                id="product-filter"
                placeholder="Filter products..."
                class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 dark:bg-gray-700 dark:text-white"
            />
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-1 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">SKU</th>
                        <th class="px-4 py-1 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">UPC</th>
                        <th class="px-4 py-1 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Name</th>
                        <th class="px-4 py-1 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Price</th>
                        <th class="px-4 py-1"></th> {{-- Edit column --}}
                    </tr>
                </thead>
                <tbody id="products-table-body" class="bg-white dark:bg-gray-800">
                    @foreach ($products as $product)
                        <tr class="product-row border-b border-gray-200 dark:border-gray-700">
                            <td class="px-4 py-0 text-sm text-gray-800 dark:text-gray-100">{{ $product->sku }}</td>
                            <td class="px-4 py-0 text-sm text-gray-800 dark:text-gray-100">{{ $product->upc }}</td>
                            <td class="px-4 py-0 text-sm text-gray-800 dark:text-gray-100">{{ $product->name }}</td>
                            <td class="px-4 py-0 text-sm text-gray-800 dark:text-gray-100">${{ number_format($product->price, 2) }}</td>
                            <td class="px-4 py-0 text-right">
                                <a href="{{ route('pos.products.edit', $product->id) }}"
                                   class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600 font-semibold">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterInput = document.getElementById('product-filter');
            const tableBody = document.getElementById('products-table-body');
            const rows = tableBody.querySelectorAll('.product-row');

            filterInput.addEventListener('input', function () {
                const filterText = this.value.toLowerCase();

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(filterText) ? '' : 'none';
                });
            });
        });
    </script>
</x-app-layout>
