<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
            Staff Directory
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <div class="flex flex-wrap justify-between items-center mb-4 gap-4">
                <!-- Filter input (left) -->
                <div class="flex-grow max-w-sm">
                    <input id="staff-filter" type="text"
                        placeholder="Filter staff..."
                        class="w-full px-3 py-2 border rounded text-sm bg-white dark:bg-gray-900 text-black dark:text-white border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                <!-- Buttons (right) -->
                <div class="flex gap-2 flex-shrink-0">
                    <a href="{{ route('staff.create') }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                        + Add Staff
                    </a>
                    <a href="{{ route('staff.index', ['showPast' => $showPast ? 0 : 1]) }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                        {{ $showPast ? 'Hide Past Staff' : 'Show Past Staff' }}
                    </a>
                </div>
            </div>

            <div id="staff-table">
                @include('staff.partials.table', ['staff' => $staff, 'showPast' => $showPast])
            </div>
        </div>
    </div>

    <script>
        document.getElementById('staff-filter').addEventListener('input', function () {
            const query = this.value;

            fetch(`/staff?filter=${encodeURIComponent(query)}{{ $showPast ? '&showPast=1' : '' }}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('staff-table').innerHTML = html;
            });
        });
    </script>
</x-app-layout>
