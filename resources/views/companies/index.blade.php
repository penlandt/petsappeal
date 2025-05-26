<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
            Companies
        </h2>
    </x-slot>

    <div class="py-12 max-w-6xl mx-auto">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <h3 class="text-lg font-bold mb-4">Company List</h3>

            @if ($companies->isEmpty())
                <p>No companies found.</p>
            @else
                <table class="w-full table-auto">
                    <thead>
                        <tr>
                            <th class="text-left">Name</th>
                            <th class="text-left">Email</th>
                            <th class="text-left">Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($companies as $company)
                            <tr class="border-t border-gray-600">
                                <td>{{ $company->name }}</td>
                                <td>{{ $company->email }}</td>
                                <td>{{ $company->phone }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</x-app-layout>
