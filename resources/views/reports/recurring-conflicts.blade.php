<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Recurring Appointment Conflicts
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 text-green-600 dark:text-green-400 font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4">
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Print Report
            </button>
        </div>

        @if($conflicts->isEmpty())
            <p class="text-gray-800 dark:text-gray-200">No unresolved conflicts found.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-800 dark:text-gray-100 border dark:border-gray-600">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                        <tr>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Time</th>
                            <th class="px-4 py-2">Staff</th>
                            <th class="px-4 py-2">Location</th>
                            <th class="px-4 py-2">Reason</th>
                            <th class="px-4 py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($conflicts as $conflict)
                            <tr class="border-t dark:border-gray-600">
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($conflict->conflict_date)->format('M j, Y') }}</td>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($conflict->conflict_time)->format('g:i A') }}</td>
                                <td class="px-4 py-2">{{ $conflict->staff->first_name }} {{ $conflict->staff->last_name }}</td>
                                <td class="px-4 py-2">
                                    {{ $conflict->staff->location->name }} ({{ $conflict->staff->location->city }}, {{ $conflict->staff->location->state }} {{ $conflict->staff->location->postal_code }})
                                </td>
                                <td class="px-4 py-2">{{ $conflict->reason }}</td>
                                <td class="px-4 py-2">
                                    <form method="POST" action="{{ route('reports.recurring-conflicts.delete', $conflict->id) }}"
                                          onsubmit="return confirm('Delete this conflict?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
