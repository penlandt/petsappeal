<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Retail Product Inventory
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                @if (session('success'))
                    <div class="mb-4 text-green-600 dark:text-green-400 font-semibold whitespace-pre-line">
                        {!! nl2br(e(session('success'))) !!}
                    </div>
                @endif

                <div class="flex justify-between items-center mb-4">
                    <input type="text"
                           placeholder="Search by product name..."
                           class="w-1/2 px-3 py-2 rounded border border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white"
                           oninput="filterProducts(this.value)">
                    <a href="{{ route('pos.products') }}"
                       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        + Add New Product
                    </a>
                </div>

                <table class="w-full table-auto text-left border border-gray-200 dark:border-gray-700">
                    <thead class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                        <tr>
                            <th class="px-4 py-2">Product Name</th>
                            <th class="px-4 py-2">SKU</th>
                            <th class="px-4 py-2">Price</th>
                            <th class="px-4 py-2">Cost</th>
                            <th class="px-4 py-2">Quantity</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody">
                        @forelse ($products as $product)
                            <tr class="border-t border-gray-300 dark:border-gray-600">
                                <td class="px-4 py-2">{{ $product->name }}</td>
                                <td class="px-4 py-2">{{ $product->sku }}</td>
                                <td class="px-4 py-2">${{ number_format($product->price, 2) }}</td>
                                <td class="px-4 py-2">${{ number_format($product->cost, 2) }}</td>
                                <td class="px-4 py-2">{{ $product->quantity }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-2 text-center text-gray-500">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function filterProducts(query) {
            const rows = document.querySelectorAll("#productTableBody tr");
            query = query.toLowerCase();
            rows.forEach(row => {
                const name = row.cells[0].textContent.toLowerCase();
                row.style.display = name.includes(query) ? "" : "none";
            });
        }
    </script>
</x-app-layout>
