<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>End of Day Report – {{ $location->name }}</title>
    <style>
        body {
            font-family: sans-serif;
            background: white;
            color: #000;
            padding: 40px;
        }
        h1 {
            font-size: 24px;
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
        }
        th, td {
            border: 1px solid #999;
            padding: 8px;
        }
        th {
            background: #eee;
            text-align: left;
        }
        .currency {
            text-align: right;
            white-space: nowrap;
        }
        .total {
            font-weight: bold;
        }
        .timestamp {
            text-align: right;
            font-size: 12px;
            margin-bottom: 10px;
            color: #666;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="timestamp">
        Report Generated: {{ \Carbon\Carbon::now($timezone)->format('F j, Y g:i A') }}
    </div>

    <h1>End of Day Report – {{ $location->name }}<br>
    {{ \Carbon\Carbon::now($timezone)->format('F j, Y') }}</h1>

    <table>
        <thead>
            <tr>
                <th>Sale ID</th>
                <th>Time</th>
                <th>Client</th>
                <th>Type</th>
                <th class="currency">Subtotal</th>
                <th class="currency">Tax</th>
                <th class="currency">Discount</th>
                <th class="currency">Loyalty Earned</th>
                <th class="currency">Loyalty Redeemed</th>
                <th class="currency">Total</th>
                <th>Payment Method</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalSubtotal = 0;
                $totalTax = 0;
                $totalDiscount = 0;
                $totalPointsEarned = 0;
                $totalPointsRedeemed = 0;
                $totalOverall = 0;
            @endphp

            @foreach($sales as $sale)
                @php
                    $subtotal = $sale->subtotal ?? 0;
                    $tax = $sale->tax ?? 0;
                    $discount = $sale->discount ?? 0;
                    $earned = $sale->points_earned ?? 0;
                    $redeemed = $sale->points_redeemed ?? 0;
                    $total = $subtotal + $tax - $discount;

                    $totalSubtotal += $subtotal;
                    $totalTax += $tax;
                    $totalDiscount += $discount;
                    $totalPointsEarned += $earned;
                    $totalPointsRedeemed += $redeemed;
                    $totalOverall += $total;
                @endphp
                <tr>
                    <td>{{ $sale->id }}</td>
                    <td>{{ $sale->created_at->timezone($timezone)->format('g:i A') }}</td>
                    <td>{{ $sale->client->full_name ?? 'N/A' }}</td>
                    <td>{{ $sale->invoice_id ? 'Invoice' : 'Product' }}</td>
                    <td class="currency">${{ number_format($subtotal, 2) }}</td>
                    <td class="currency">${{ number_format($tax, 2) }}</td>
                    <td class="currency">${{ number_format($discount, 2) }}</td>
                    <td class="currency">{{ $earned }}</td>
                    <td class="currency">{{ $redeemed }}</td>
                    <td class="currency">${{ number_format($total, 2) }}</td>
                    <td>{{ $sale->payment_method ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total">
                <td colspan="4">Totals</td>
                <td class="currency">${{ number_format($totalSubtotal, 2) }}</td>
                <td class="currency">${{ number_format($totalTax, 2) }}</td>
                <td class="currency">${{ number_format($totalDiscount, 2) }}</td>
                <td class="currency">{{ $totalPointsEarned }}</td>
                <td class="currency">{{ $totalPointsRedeemed }}</td>
                <td class="currency">${{ number_format($totalOverall, 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="no-print" style="text-align: center; margin-top: 40px;">
        <button onclick="window.print()">Print Report</button>
    </div>

</body>
</html>
