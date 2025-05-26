<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
            Locations
        </h2>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow p-6 rounded-lg">
            <div class="flex justify-between items-center mb-4">
                <a href="{{ route('locations.create') }}"
                   class="text-sm font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                    + Add New Location
                </a>

                @if ($showInactive)
                    <a href="{{ route('locations.index') }}"
                       class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                        Hide Inactive
                    </a>
                @else
                    <a href="{{ route('locations.index', ['show_inactive' => 1]) }}"
                       class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                        Show Inactive
                    </a>
                @endif
            </div>

            @if ($locations->isEmpty())
                <p class="text-gray-700 dark:text-gray-300">No locations found.</p>
            @else
                <table class="w-full text-left table-auto">
                    <thead>
                        <tr class="border-b border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100">
                            <th class="pb-2">Name</th>
                            <th class="pb-2">Address</th>
                            <th class="pb-2">Phone</th>
                            <th class="pb-2">Email</th>
                            <th class="pb-2">Status</th>
                            <th class="pb-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($locations as $location)
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="py-2 text-gray-900 dark:text-gray-100">{{ $location->name }}</td>
                                <td class="py-2 text-gray-900 dark:text-gray-100">
                                    {{ $location->address }},
                                    {{ $location->city }}, {{ $location->state }} {{ $location->postal_code }}
                                </td>
                                <td class="py-2 text-gray-900 dark:text-gray-100">{{ $location->phone }}</td>
                                <td class="py-2 text-gray-900 dark:text-gray-100">{{ $location->email }}</td>
                                <td class="py-2">
                                    @if ($location->inactive)
                                        <span class="text-sm text-red-500">Inactive</span>
                                    @else
                                        <span class="text-sm text-green-600">Active</span>
                                    @endif
                                </td>
                                <td class="py-2">
                                    <a href="{{ route('locations.edit', $location->id) }}"
                                       class="inline-block px-3 py-1 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</x-app-layout>
