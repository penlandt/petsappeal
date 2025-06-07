<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sales Receipt</title>
</head>
<body style="font-family: Arial, sans-serif; color: #000;">
    <div style="max-width: 600px; margin: auto;">
        <div style="text-align: center;">
            <img src="{{ url('/company-assets/logo/' . $sale->location->company_id) }}" alt="Company Logo" style="max-height: 100px; margin-bottom: 10px;">
            <h2>Sales Receipt</h2>
            <p>Receipt #: {{ $sale->id }}</p>
        </div>

        <p><strong>Date:</strong> {{ $sale->created_at->format('F j, Y g:i A') }}</p>
        <p><strong>Location:</strong> {{ $sale->location->name }}</p>
        <p>{{ $sale->location->address }}</p>
        <p>{{ $sale->location->city }}, {{ $sale->location->state }} {{ $sale->location->postal_code }}</p>

        <hr>

        <table width="100%" cellpadding="4" cellspacing="0" border="1" style="border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr>
                    <th align="left">Item</th>
                    <th align="left">Qty</th>
                    <th align="left">Price</th>
                    <th align="right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td align="right">${{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p><strong>Subtotal:</strong> ${{ number_format($sale->subtotal, 2) }}</p>
        <p><strong>Tax:</strong> ${{ number_format($sale->tax, 2) }}</p>
        <p><strong>Total:</strong> <strong>${{ number_format($sale->total, 2) }}</strong></p>

        @if ($sale->payments && $sale->payments->count())
            <h4>Payments</h4>
            <ul>
                @foreach ($sale->payments as $payment)
                    <li>{{ $payment->method }} - ${{ number_format($payment->amount, 2) }}</li>
                @endforeach
            </ul>
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
            <h4>Loyalty</h4>
            @if ($earned > 0)
                <p>Points Earned: {{ number_format($earned, 2) }}</p>
            @endif
            @if ($redeemed > 0)
                <p>Points Redeemed: {{ number_format($redeemed, 2) }}</p>
                <p>Loyalty Discount: -${{ number_format($discount, 2) }}</p>
            @endif
        @endif
    </div>
</body>
</html>
