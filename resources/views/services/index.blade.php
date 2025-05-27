<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
            Services
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow p-6 rounded-lg">

            <div class="flex flex-wrap justify-between items-center mb-4 gap-4">
                <!-- Filter input (left) -->
                <div class="flex-grow max-w-sm">
                    <input id="service-filter" type="text"
                        placeholder="Filter services..."
                        class="w-full px-3 py-2 border rounded text-sm bg-white dark:bg-gray-900 text-black dark:text-white border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                <!-- Buttons (right) -->
                <div class="flex gap-2 flex-shrink-0">
                    <a href="{{ route('services.create') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                        + Add New Service
                    </a>

                    @if ($showInactive)
                        <a href="{{ route('services.index') }}"
                            class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                            Hide Inactive
                        </a>
                    @else
                        <a href="{{ route('services.index', ['show_inactive' => 1]) }}"
                            class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                            Show Inactive
                        </a>
                    @endif
                </div>
            </div>

            <div id="services-table">
                @include('services.partials.table', ['services' => $services])
            </div>
        </div>
    </div>

    <script>
        document.getElementById('service-filter').addEventListener('input', function () {
            const query = this.value;

            fetch(`/services?filter=${encodeURIComponent(query)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.text())
                .then(html => {
                    document.getElementById('services-table').innerHTML = html;
                });
        });
    </script>
</x-app-layout>
