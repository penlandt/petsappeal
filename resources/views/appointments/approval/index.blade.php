<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            Pending Appointment Requests
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if ($appointments->isEmpty())
            <p class="text-gray-700 dark:text-gray-300">There are no pending appointments to review.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Client</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pet</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Service</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Time</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($appointments as $appt)
                            <tr>
                                <td class="px-4 py-2 text-gray-900 dark:text-gray-100">
                                    {{ $appt->pet->client->first_name }} {{ $appt->pet->client->last_name }}
                                </td>
                                <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $appt->pet->name }}</td>
                                <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $appt->service->name }}</td>
                                <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($appt->date)->format('M j, Y') }}</td>
                                <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($appt->time)->format('g:i A') }}</td>
                                <td class="px-4 py-2 text-right">
                                    <a href="{{ route('appointments.approval.edit', $appt->id) }}"
                                       class="text-blue-600 dark:text-blue-400 hover:underline">Review</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
