<x-client-layout>
    <div class="py-6 px-4 sm:px-6 lg:px-8">

        {{-- Filter and Add Button Row --}}
        <div class="flex items-center justify-between mb-4">
            {{-- Show Inactive Filter --}}
            <form method="GET" action="{{ route('client.pets.index') }}">
                <label for="show_inactive" class="inline-flex items-center text-sm text-gray-700 dark:text-gray-300">
                    <input type="checkbox" name="show_inactive" id="show_inactive" class="mr-2"
                           onchange="this.form.submit()"
                           {{ request('show_inactive') ? 'checked' : '' }}>
                    Show Inactive
                </label>
            </form>

            {{-- Add New Pet Button --}}
            <a href="{{ route('client.pets.create') }}"
               class="inline-block px-4 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700">
                + Add New Pet
            </a>
        </div>

        {{-- Pet Table --}}
        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Name</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Species</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Breed</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Birthdate</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Inactive?</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($pets as $pet)
                        <tr class="text-sm">
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $pet->name }}</td>
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $pet->species }}</td>
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $pet->breed }}</td>
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ \Illuminate\Support\Carbon::parse($pet->birthdate)->format('Y-m-d') }}</td>
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $pet->inactive ? 'Yes' : 'No' }}</td>
                            <td class="px-4 py-2 text-right">
                                <a href="{{ route('client.pets.edit', $pet) }}"
                                   class="text-indigo-600 hover:underline">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-gray-600 dark:text-gray-300">
                                No pets found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-client-layout>
