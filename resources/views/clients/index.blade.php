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

            <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
                <input type="text" id="clientSearch" placeholder="Search clients..."
                    class="px-3 py-2 w-full md:w-1/3 border border-gray-300 rounded dark:bg-gray-700 dark:text-white dark:border-gray-600"
                    onkeyup="filterClients()">

                <a href="{{ route('clients.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 h-10 flex items-center justify-center rounded whitespace-nowrap"
                    style="min-width: 150px;">
                    + New Client
                </a>
            </div>

            <table class="w-full text-left text-sm mt-4" id="clientTable">
                <thead>
                    <tr class="border-b border-gray-300 dark:border-gray-600">
                        <th class="py-0.5">Name</th>
                        <th class="py-0.5">Email</th>
                        <th class="py-0.5">Phone</th>
                        <th class="text-left text-sm text-gray-700 dark:text-gray-300 py-0.5">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clients as $client)
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <td class="py-0.5">
                                <a href="{{ route('clients.show', $client->id) }}"
                                    class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $client->first_name }} {{ $client->last_name }}
                                </a>
                            </td>
                            <td class="py-0.5">{{ $client->email ?? '-' }}</td>
                            <td class="py-0.5">{{ $client->phone ?? '-' }}</td>
                            <td class="py-0.5 space-x-4">
                                <a href="{{ route('clients.edit', $client) }}"
                                    class="text-blue-600 dark:text-blue-400 hover:underline">
                                    Edit
                                </a>
                                <a href="{{ route('clients.history', $client) }}"
                                class="text-blue-600 dark:text-blue-400 hover:underline">
                                History
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

    <script>
        function filterClients() {
            const input = document.getElementById('clientSearch');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('clientTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                let match = false;

                for (let j = 0; j < cells.length - 1; j++) {
                    if (cells[j].textContent.toLowerCase().includes(filter)) {
                        match = true;
                        break;
                    }
                }

                rows[i].style.display = match ? '' : 'none';
            }
        }
    </script>
</x-app-layout>
