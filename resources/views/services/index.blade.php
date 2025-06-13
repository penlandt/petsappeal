<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
            Services
            <span class="relative inline-block align-middle ml-2" x-data="{ show: false }">
                <svg @mouseenter="show = true" @mouseleave="show = false"
                    class="w-5 h-5 text-blue-500 cursor-pointer"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8 8 3.582 8 8zm-9-1h2v5H9v-5zm0-4h2v2H9V5z" clip-rule="evenodd" />
                </svg>
                <div x-show="show" x-cloak
                    class="absolute z-50 bg-gray-700 text-white text-sm rounded py-2 px-3 bottom-full mb-2 left-0 w-64 whitespace-normal shadow-lg">
                    Services are what your business provides to customers — from simple nail trims to full grooming packages. Describe them in as much or as little detail as you like — PETSAppeal takes care of the rest!
                </div>
            </span>
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
