<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
            Staff Directory
            <span class="relative inline-block align-middle ml-2" x-data="{ show: false }">
                <svg @mouseenter="show = true" @mouseleave="show = false"
                    class="w-5 h-5 text-blue-500 cursor-pointer"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8 8 3.582 8 8zm-9-1h2v5H9v-5zm0-4h2v2H9V5z" clip-rule="evenodd" />
                </svg>
                <div x-show="show" x-cloak
                    class="absolute z-50 bg-gray-700 text-white text-sm rounded py-2 px-3 bottom-full mb-2 left-0 w-64 whitespace-normal shadow-lg">
                    Staff are the heart of your grooming operation. Whether they’re employees or independent contractors, PETSAppeal makes it easy to manage everyone in one place.
                </div>
            </span>
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
