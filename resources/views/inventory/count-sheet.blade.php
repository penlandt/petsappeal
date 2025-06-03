<x-app-layout>
<x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            Inventory Count Sheet – {{ $location->name }}
        </h2>

        @include('pos.partials.secondary-menu')
    </div>
</x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('inventory.reconcile') }}">
                @csrf
                <input type="hidden" name="location_id" value="{{ $location->id }}">

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white dark:bg-gray-800 text-sm border border-gray-300 dark:border-gray-700">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-left">
                            <tr>
                                <th class="p-2 border-b">Product</th>
                                <th class="p-2 border-b">SKU</th>
                                <th class="p-2 border-b">UPC</th>
                                <th class="p-2 border-b text-right">System QOH</th>
                                <th class="p-2 border-b text-right">Actual Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inventories as $item)
                                <tr>
                                    <td class="p-2 border-b text-gray-900 dark:text-gray-100">
                                        {{ $item->product->name }}
                                    </td>
                                    <td class="p-2 border-b text-gray-900 dark:text-gray-100">
                                        {{ $item->product->sku ?? '—' }}
                                    </td>
                                    <td class="p-2 border-b text-gray-900 dark:text-gray-100">
                                        {{ $item->product->upc ?? '—' }}
                                    </td>
                                    <td class="p-2 border-b text-right text-gray-900 dark:text-gray-100">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="p-2 border-b text-right">
                                        <input
                                            type="number"
                                            name="counts[{{ $item->inventory_id ?? 'new_' . $item->product->id }}]"
                                            step="1"
                                            class="w-24 px-2 py-1 border rounded-md text-right text-gray-900 dark:text-gray-100 dark:bg-gray-700"
                                            style="background-color: #fff; color: #000;"
                                        />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 text-right">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Submit Counts
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
