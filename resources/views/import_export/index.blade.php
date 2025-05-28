<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Import / Export Data
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
        
        @if (session('success'))
            <div class="mb-4 px-4 py-2 rounded bg-green-100 text-green-800 dark:bg-green-700 dark:text-white">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 px-4 py-2 rounded bg-red-100 text-red-800 dark:bg-red-700 dark:text-white">
                {{ session('error') }}
            </div>
        @endif
        
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Export Data</h3>
            <div class="space-x-4 mb-8">
                <a href="{{ route('export.clients') }}"
                   class="export-button bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Export Clients
                </a>
                <a href="{{ route('export.pets') }}"
                   class="export-button bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Export Pets
                </a>
                <a href="{{ route('export.services') }}"
                   class="export-button bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Export Services
                </a>
            </div>

            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Import Data</h3>
            <form id="importForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="import_type" class="block mb-1 font-medium text-gray-800 dark:text-white">Select Type</label>
                    <select name="import_type" id="import_type"
                            class="w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded" required>
                        <option value="clients">Clients</option>
                        <option value="pets">Pets</option>
                        <option value="services">Services</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="import_file" class="block mb-1 font-medium text-gray-800 dark:text-white">CSV File</label>
                    <input type="file" name="import_file" id="import_file"
                           class="w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded" required>
                </div>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Import Selected Type
                </button>
            </form>
        </div>
    </div>

    <!-- Loading Spinner Overlay -->
    <div id="loading-overlay"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <img src="/images/petsappeal-logo-square.png" class="w-20 h-20 animate-spin" alt="Loading...">
    </div>

    <script>
        document.querySelectorAll('.export-button').forEach(btn => {
            btn.addEventListener('click', function () {
                const overlay = document.getElementById('loading-overlay');
                overlay.style.display = 'flex';

                // Automatically hide loader after 5 seconds
                setTimeout(() => {
                    overlay.style.display = 'none';
                }, 5000);
            });
        });

        document.getElementById('importForm').addEventListener('submit', function (e) {
            const type = document.getElementById('import_type').value;
            let actionUrl = '';

            switch (type) {
                case 'clients':
                    actionUrl = '{{ route('import.clients') }}';
                    break;
                case 'pets':
                    actionUrl = '{{ route('import.pets') }}';
                    break;
                case 'services':
                    actionUrl = '{{ route('import.services') }}';
                    break;
                default:
                    alert('Invalid import type selected.');
                    e.preventDefault();
                    return;
            }

            this.action = actionUrl;
        });
    </script>
</x-app-layout>
