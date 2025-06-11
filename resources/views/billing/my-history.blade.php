<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            My Subscription History
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    Invoice History
                </h3>

                @if ($invoices->isEmpty())
                    <p class="text-gray-600 dark:text-gray-400">No invoices found for your company.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left text-gray-600 dark:text-gray-300">
                            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                                <tr>
                                    <th class="px-4 py-2">Date</th>
                                    <th class="px-4 py-2">Amount</th>
                                    <th class="px-4 py-2">Status</th>
                                    <th class="px-4 py-2">Invoice</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $invoice)
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="px-4 py-2">
                                            {{ $invoice->date()->toFormattedDateString() }}
                                        </td>
                                        <td class="px-4 py-2">
                                            {{ $invoice->total() }}
                                        </td>
                                        <td class="px-4 py-2 capitalize">
                                            {{ $invoice->status }}
                                        </td>
                                        <td class="px-4 py-2">
                                            <a href="{{ $invoice->hosted_invoice_url }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">
                                                View Invoice
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
