<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Boarding Units
            </h2>
            @include('modules.boarding.partials.secondary-menu')
            <a href="{{ route('boarding.units.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                + Add Unit
            </a>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-800 rounded dark:bg-green-800 dark:border-green-300 dark:text-white">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded p-4">
            <div class="flex justify-between items-center mb-4">
                <input type="text" id="unit-filter" placeholder="Filter by name, type, or size"
                       class="w-full sm:w-1/2 rounded-md shadow-sm px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                       style="background-color: #fff; color: #000;">
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
                    <thead class="bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <tr>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Type</th>
                            <th class="px-4 py-2">Size</th>
                            <th class="px-4 py-2">Max Occupancy</th>
                            <th class="px-4 py-2">Price/Night</th>
                            <th class="px-4 py-2 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="unit-table-body">
                        @foreach ($units as $unit)
                            <tr class="border-b border-gray-200 dark:border-gray-600">
                                <td class="px-4 py-2">{{ $unit->name }}</td>
                                <td class="px-4 py-2">{{ ucfirst($unit->type) }}</td>
                                <td class="px-4 py-2">{{ ucfirst($unit->size) }}</td>
                                <td class="px-4 py-2">{{ $unit->max_occupants }}</td>
                                <td class="px-4 py-2">${{ number_format($unit->price_per_night, 2) }}</td>
                                <td class="px-4 py-2 text-right">
                                    <a href="{{ route('boarding.units.edit', $unit->id) }}"
                                       class="text-blue-600 dark:text-blue-400 hover:underline">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                        @if ($units->isEmpty())
                            <tr>
                                <td colspan="6" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">
                                    No units found for this location.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('unit-filter').addEventListener('input', function () {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#unit-table-body tr');
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
</x-app-layout>
