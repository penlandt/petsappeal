<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Pet List
            <span class="relative inline-block align-middle ml-2" x-data="{ show: false }">
                <svg @mouseenter="show = true" @mouseleave="show = false"
                    class="w-5 h-5 text-blue-500 cursor-pointer"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8 8 3.582 8 8zm-9-1h2v5H9v-5zm0-4h2v2H9V5z" clip-rule="evenodd" />
                </svg>
                <div x-show="show" x-cloak
                    class="absolute z-50 bg-gray-700 text-white text-sm rounded py-2 px-3 bottom-full mb-2 left-0 w-64 whitespace-normal shadow-lg">
                    Pets are at the center of everything you do. Keep detailed records for every client pet right here.
                </div>
            </span>
        </h2>
    </x-slot>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-2 flex-wrap gap-2">
            <input type="text"
                   id="petFilter"
                   placeholder="Filter by pet or owner name..."
                   class="w-full md:w-1/2 px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-400 text-sm">

            <a href="{{ route('pets.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 whitespace-nowrap">
                + Add New Pet
            </a>
        </div>

        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded shadow">
            <table id="petTable" class="min-w-full text-sm text-left text-gray-900 dark:text-gray-100 border-collapse">
                <thead class="bg-gray-100 dark:bg-gray-700 text-xs uppercase text-gray-600 dark:text-gray-300">
                    <tr>
                        <th class="px-2 border-b border-gray-300 dark:border-gray-600">Pet Name</th>
                        <th class="px-2 border-b border-gray-300 dark:border-gray-600">Owner</th>
                        <th class="px-2 border-b border-gray-300 dark:border-gray-600">Species</th>
                        <th class="px-2 border-b border-gray-300 dark:border-gray-600">Breed</th>
                        <th class="px-2 border-b border-gray-300 dark:border-gray-600">Birthdate</th>
                        <th class="px-2 border-b border-gray-300 dark:border-gray-600">Status</th>
                        <th class="px-2 border-b border-gray-300 dark:border-gray-600 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pets as $pet)
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <td class="px-2 pet-name">{{ $pet->name }}</td>
                            <td class="px-2 owner-name">{{ $pet->client->first_name }} {{ $pet->client->last_name }}</td>
                            <td class="px-2">{{ $pet->species ?? '-' }}</td>
                            <td class="px-2">{{ $pet->breed ?? '-' }}</td>
                            <td class="px-2">
                                {{ $pet->birthdate ? \Carbon\Carbon::parse($pet->birthdate)->format('M j, Y') : '-' }}
                            </td>
                            <td class="px-2">
                                {{ $pet->inactive ? 'Inactive' : 'Active' }}
                            </td>
                            <td class="px-2 text-right">
                                <a href="{{ route('pets.edit', $pet->id) }}"
                                   class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-2 text-center text-gray-500 dark:text-gray-300">
                                No pets found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterInput = document.getElementById('petFilter');
            const rows = document.querySelectorAll('#petTable tbody tr');

            filterInput.addEventListener('input', function () {
                const filterValue = this.value.toLowerCase();

                rows.forEach(row => {
                    const pet = row.querySelector('.pet-name')?.textContent.toLowerCase() || '';
                    const owner = row.querySelector('.owner-name')?.textContent.toLowerCase() || '';

                    if (pet.includes(filterValue) || owner.includes(filterValue)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
</x-app-layout>
