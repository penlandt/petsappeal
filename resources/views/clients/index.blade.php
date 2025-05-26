<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
            Clients
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">

@if (session('success'))
    <div class="mb-4 text-green-600 dark:text-green-300">
        {{ session('success') }}
    </div>
@endif


            <a href="{{ route('clients.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mb-4 inline-block">
                + New Client
            </a>

            <table class="w-full text-left text-sm mt-4">
                <thead>
                    <tr class="border-b border-gray-300 dark:border-gray-600">
                        <th class="py-2">Name</th>
                        <th class="py-2">Email</th>
                        <th class="py-2">Phone</th>
                        <th class="text-left text-sm text-gray-700 dark:text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clients as $client)
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <td class="...">
                                <a href="{{ route('clients.show', $client->id) }}"
                                    class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $client->first_name }} {{ $client->last_name }}
                                </a>
                            </td>
                            <td class="py-2">{{ $client->email ?? '-' }}</td>
                            <td class="py-2">{{ $client->phone ?? '-' }}</td>
                            <td>
                                <a href="{{ route('clients.edit', $client) }}"
                                class="text-blue-600 dark:text-blue-400 hover:underline">
                                Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-4 text-center text-gray-500">No clients found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
