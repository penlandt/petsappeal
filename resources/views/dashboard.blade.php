<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 leading-tight">
            PETSAppeal Dashboard
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 px-6">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <p class="text-gray-800 dark:text-gray-100 text-lg mb-4">
                Welcome, {{ Auth::user()->name }}. What would you like to manage?
            </p>

            <ul class="list-disc ml-6 text-gray-700 dark:text-gray-200">
                <li><a href="{{ route('companies.index') }}" class="text-blue-500 hover:underline">Manage Companies</a></li>
                <li>Clients (coming soon)</li>
                <li>Pets (coming soon)</li>
                <li>Appointments (coming soon)</li>
                <li>Services (coming soon)</li>
            </ul>
        </div>
    </div>
</x-app-layout>
