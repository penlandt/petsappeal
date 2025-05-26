<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
            Services
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow p-6 rounded-lg">

            <div class="flex justify-between items-center mb-4">
                <a href="{{ route('services.create') }}"
                   class="text-sm font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                    + Add New Service
                </a>

                @if ($showInactive)
                    <a href="{{ route('services.index') }}"
                       class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                        Hide Inactive
                    </a>
                @else
                    <a href="{{ route('services.index', ['show_inactive' => 1]) }}"
                       class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                        Show Inactive
                    </a>
                @endif
            </div>

            @if ($services->isEmpty())
                <p class="text-gray-700 dark:text-gray-300">No services found.</p>
            @else
                <table class="w-full text-left table-auto">
                    <thead>
                        <tr class="border-b border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100">
                            <th class="pb-2">Name</th>
                            <th class="pb-2">Duration</th>
                            <th class="pb-2">Price</th>
                            <th class="pb-2">Status</th>
                            <th class="pb-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $service)
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="py-2 text-gray-900 dark:text-gray-100">{{ $service->name }}</td>
                                <td class="py-2 text-gray-900 dark:text-gray-100">{{ $service->duration }} min</td>
                                <td class="py-2 text-gray-900 dark:text-gray-100">${{ number_format($service->price, 2) }}</td>
                                <td class="py-2">
                                    @if ($service->inactive)
                                        <span class="text-sm text-red-500">Inactive</span>
                                    @else
                                        <span class="text-sm text-green-600">Active</span>
                                    @endif
                                </td>
				<td class="py-2">
                                    <a href="{{ route('services.edit', $service->id) }}"
                                        class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
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
