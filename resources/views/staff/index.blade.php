<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
            Staff Directory
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-2">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Current Staff</h3>
                <div class="flex gap-4">
                    <a href="{{ route('staff.create') }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                        + Add Staff
                    </a>
                    <a href="{{ route('staff.index', ['showPast' => $showPast ? 0 : 1]) }}"
                       class="text-sm text-blue-600 dark:text-blue-300 hover:underline mt-2 md:mt-0">
                        {{ $showPast ? 'Hide Past Staff' : 'Show Past Staff' }}
                    </a>
                </div>
            </div>

            @if ($staff->isEmpty())
                <p class="text-gray-700 dark:text-gray-300">No staff members found.</p>
            @else
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-gray-300 dark:border-gray-600">
                            <th class="py-2">Name</th>
                            <th class="py-2">Job Title</th>
                            <th class="py-2">Phone</th>
                            <th class="py-2">Email</th>
                            <th class="py-2">Type</th>
                            @if ($showPast)
                                <th class="py-2">End Date</th>
                            @endif
                            <th class="py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($staff as $person)
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="py-2 text-gray-900 dark:text-gray-100">{{ $person->first_name }} {{ $person->last_name }}</td>
                                <td class="py-2 text-gray-700 dark:text-gray-300">{{ $person->job_title }}</td>
                                <td class="py-2 text-gray-700 dark:text-gray-300">{{ $person->phone }}</td>
                                <td class="py-2 text-gray-700 dark:text-gray-300">{{ $person->email }}</td>
                                <td class="py-2 text-gray-700 dark:text-gray-300">{{ $person->type }}</td>
                                @if ($showPast)
                                    <td class="py-2 text-gray-700 dark:text-gray-300">
                                        {{ $person->end_date ?? '-' }}
                                    </td>
                                @endif
                                <td class="py-2">
                                    <a href="{{ route('staff.edit', $person->id) }}"
                                       class="text-sm text-blue-600 dark:text-blue-300 hover:underline">
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
