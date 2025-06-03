<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
            History for {{ $client->first_name }} {{ $client->last_name }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow text-gray-900 dark:text-gray-100">
        <table class="w-full text-left text-sm mt-6 border-t border-gray-300 dark:border-gray-600">
    <thead>
        <tr class="border-b border-gray-300 dark:border-gray-600">
            <th class="py-2">Date</th>
            <th class="py-2">Type</th>
            <th class="py-2">Location</th>
            <th class="py-2">Amount</th>
            <th class="py-2">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($history as $item)
            <tr class="border-b border-gray-200 dark:border-gray-700">
                <td class="py-2">
                    {{ $item->created_at->format('M j, Y') }}
                </td>
                <td class="py-2 capitalize">
                    {{ $item->type }}
                </td>
                <td class="py-2">
                    {{ $item->location->name ?? '-' }}
                </td>
                <td class="py-2">
                    ${{ number_format($item->total_amount ?? $item->total, 2) }}
                </td>
                <td class="py-2">
                    @if ($item->type === 'invoice')
                        <a href="{{ route('invoices.print', $item->id) }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">
                            View Invoice
                        </a>
                    @else
                        <a href="{{ url("/pos/sales/{$item->id}/receipt") }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">
                            View Receipt
                        </a>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="py-4 text-center text-gray-500">No history found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

        </div>
    </div>
</x-app-layout>
