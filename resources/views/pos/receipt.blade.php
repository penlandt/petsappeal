<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            Sales Receipt
        </h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto bg-white dark:bg-gray-800 shadow-md rounded px-6 print:px-0 print:shadow-none print:bg-white print:dark:bg-white">
        <div class="text-center mb-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Sales Receipt</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300">Receipt #: {{ $sale->id }}</p>
        </div>

        <div class="mb-4 text-sm text-gray-800 dark:text-gray-100">
            <p><strong>Date:</strong> {{ $sale->created_at->format('F j, Y g:i A') }}</p>
            @if ($sale->location)
                <p><strong>Location:</strong> {{ $sale->location->name }}</p>
                <p>{{ $sale->location->address }}</p>
                <p>{{ $sale->location->city }}, {{ $sale->location->state }} {{ $sale->location->postal_code }}</p>
            @endif
        </div>

        <table class="w-full text-sm border-t border-b border-gray-300 dark:border-gray-600 mb-4">
            <thead>
                <tr class="text-left text-gray-700 dark:text-gray-200">
                    <th class="py-2">Item</th>
                    <th class="py-2">Qty</th>
                    <th class="py-2">Price</th>
                    <th class="py-2 text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->items as $item)
                    <tr class="border-t border-gray-200 dark:border-gray-600 text-gray-900 dark:text-gray-100">
                        <td class="py-1">{{ $item->name }}</td>
                        <td class="py-1">{{ $item->quantity }}</td>
                        <td class="py-1">${{ number_format($item->price, 2) }}</td>
                        <td class="py-1 text-right">${{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-right text-gray-900 dark:text-gray-100">
            <p><strong>Subtotal:</strong> ${{ number_format($sale->subtotal, 2) }}</p>
            <p><strong>Tax:</strong> ${{ number_format($sale->tax, 2) }}</p>
            <p class="text-lg"><strong>Total:</strong> ${{ number_format($sale->total, 2) }}</p>
        </div>

        @if ($sale->payments && $sale->payments->count())
            <div class="mt-6">
                <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100">Payments</h4>
                <ul class="list-disc list-inside text-sm text-gray-800 dark:text-gray-100">
                    @foreach ($sale->payments as $payment)
                        <li>{{ $payment->method }} - ${{ number_format($payment->amount, 2) }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $earned = \App\Models\LoyaltyPointTransaction::where('pos_sale_id', $sale->id)
                ->where('type', 'earn')
                ->sum('points');

            $redeemed = \App\Models\LoyaltyPointTransaction::where('pos_sale_id', $sale->id)
                ->where('type', 'redeem')
                ->sum('points');

            $discount = 0;
            if ($redeemed > 0 && $sale->location->company && $sale->location->company->loyaltyProgram) {
                $discount = $redeemed * $sale->location->company->loyaltyProgram->point_value;
            }
        @endphp

        @if ($earned > 0 || $redeemed > 0)
            <div class="mt-6 text-sm text-gray-900 dark:text-gray-100">
                @if ($earned > 0)
                    <p><strong>Points Earned:</strong> {{ number_format($earned, 2) }}</p>
                @endif
                @if ($redeemed > 0)
                    <p><strong>Points Redeemed:</strong> {{ number_format($redeemed, 2) }}</p>
                    <p><strong>Loyalty Discount:</strong> -${{ number_format($discount, 2) }}</p>
                @endif
            </div>
        @endif

        <div class="mt-8 text-center">
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 print:hidden">
                Print Receipt
            </button>
        </div>
    </div>
</x-app-layout>
