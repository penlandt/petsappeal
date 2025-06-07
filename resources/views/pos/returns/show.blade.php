<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
            Return Details — #{{ $return->id }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow text-gray-900 dark:text-gray-100 space-y-6">

            <div>
                <h3 class="text-lg font-semibold mb-2">Return Summary</h3>
                <p><strong>Date:</strong> {{ $return->created_at->format('F j, Y g:i A') }}</p>
                <p><strong>Refund Method:</strong> {{ ucfirst($return->refund_method) }}</p>
                <p><strong>Refunded Amount:</strong> ${{ number_format($return->amount_refunded, 2) }}</p>
                @if ($return->loyalty_points_redeemed > 0 || $return->loyalty_points_restored > 0)
                    <p><strong>Loyalty Points:</strong>
                        @if ($return->loyalty_points_redeemed > 0)
                            - {{ number_format($return->loyalty_points_redeemed, 2) }} redeemed
                        @endif
                        @if ($return->loyalty_points_restored > 0)
                            &plus; {{ number_format($return->loyalty_points_restored, 2) }} restored
                        @endif
                    </p>
                @endif
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-2">Returned Items</h3>
                <table class="w-full border-t border-gray-300 dark:border-gray-600 text-sm">
                    <thead>
                        <tr class="border-b border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700">
                            <th class="py-2 px-2 text-left">Product</th>
                            <th class="py-2 px-2 text-left">Qty</th>
                            <th class="py-2 px-2 text-left">Unit Price</th>
                            <th class="py-2 px-2 text-left">Tax</th>
                            <th class="py-2 px-2 text-left">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($return->items as $item)
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="py-2 px-2">{{ $item->product->name ?? 'Unknown Product' }}</td>
                                <td class="py-2 px-2">{{ $item->quantity }}</td>
                                <td class="py-2 px-2">${{ number_format($item->price, 2) }}</td>
                                <td class="py-2 px-2">${{ number_format($item->tax_amount, 2) }}</td>
                                <td class="py-2 px-2">
                                    ${{ number_format(($item->price + $item->tax_amount) * $item->quantity, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div>
                <a href="{{ route('clients.show', $return->client_id) }}"
                   class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                    &larr; Back to Client
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
