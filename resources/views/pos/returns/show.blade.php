<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Return Receipt #{{ $return->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            color: #000;
            padding: 20px;
        }
        h1 {
            text-align: center;
            font-size: 1.75rem;
            margin-bottom: 1rem;
        }
        .section {
            margin-bottom: 1.25rem;
        }
        .section h2 {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            border-bottom: 1px solid #ccc;
            padding-bottom: 0.25rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0.5rem;
        }
        th, td {
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #eee;
        }
        .totals {
            text-align: right;
        }
    </style>
</head>
<body>
    <h1>Return Receipt #{{ $return->id }}</h1>

    <div class="section">
        <h2>Client Info</h2>
        <p><strong>Name:</strong> {{ $return->client->full_name ?? 'N/A' }}</p>
        <p><strong>Location:</strong> {{ $return->location->name ?? 'N/A' }}</p>
        <p><strong>Date:</strong> {{ $return->created_at->format('F j, Y g:i A') }}</p>
    </div>

    <div class="section">
        <h2>Returned Items</h2>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Tax</th>
                    <th>Line Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($return->items as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'Unknown' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td>${{ number_format($item->tax, 2) }}</td>
                        <td>${{ number_format($item->line_total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section totals">
        <p><strong>Subtotal:</strong> ${{ number_format($return->items->sum(fn($i) => $i->price * $i->quantity), 2) }}</p>
        <p><strong>Tax:</strong> ${{ number_format($return->items->sum('tax'), 2) }}</p>
        <p><strong>Total Refunded:</strong> ${{ number_format($return->refund_amount, 2) }}</p>
        @if ($return->points_redeemed > 0)
            <p><strong>Loyalty Points Restored:</strong> {{ $return->points_redeemed }}</p>
        @endif
        <p><strong>Refund Method:</strong> {{ $return->refund_method }}</p>
    </div>
</body>
</html>
