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
        .info-grid {
            display: grid;
            grid-template-columns: auto 1fr;
            row-gap: 0.4rem;
            column-gap: 1rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            text-align: left;
            padding: 0.5rem;
            border-bottom: 1px solid #ccc;
        }
        .summary {
            margin-top: 1rem;
            text-align: right;
        }
        .summary div {
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <h1>Return Receipt</h1>

    <div class="section">
        <div class="info-grid">
            <div><strong>Return ID:</strong></div>
            <div>#{{ $return->id }}</div>

            <div><strong>Sale ID:</strong></div>
            <div>#{{ $return->sale_id }}</div>

            <div><strong>Client:</strong></div>
            <div>{{ $return->client->full_name }}</div>

            <div><strong>Phone:</strong></div>
            <div>{{ $return->client->phone }}</div>

            <div><strong>Email:</strong></div>
            <div>{{ $return->client->email }}</div>

            <div><strong>Location:</strong></div>
            <div>{{ $return->location->name }}</div>

            <div><strong>Return Date:</strong></div>
            <div>{{ $return->created_at->format('F j, Y h:i A') }}</div>
        </div>
    </div>

    <div class="section">
        <h2>Returned Items</h2>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty Returned</th>
                    <th>Unit Price</th>
                    <th>Refund Method</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($return->items ?? [] as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'Unknown Product' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td>{{ ucfirst($return->refund_method) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @php
            $subtotal = $return->items->sum(fn($item) => $item->price * $item->quantity);
            $tax = $return->items->sum('tax');
        @endphp

        <div class="summary">
            <div><strong>Subtotal Refunded:</strong> ${{ number_format($subtotal, 2) }}</div>
            <div><strong>Tax Refunded:</strong> ${{ number_format($tax, 2) }}</div>
            <div><strong>Total Refunded:</strong> ${{ number_format($return->refund_amount, 2) }}</div>
        </div>
    </div>

    @php
        $pointValue = \App\Models\LoyaltyProgram::where('company_id', auth()->user()->company_id)->value('point_value');
        $pointsRestored = $return->points_redeemed;
        $discount = $pointsRestored * $pointValue;
    @endphp

    <div class="section summary">
        <div><strong>Loyalty Points Restored:</strong> {{ number_format($pointsRestored, 2) }}</div>
        <div><strong>Loyalty Discount Value:</strong> ${{ number_format($discount, 2) }}</div>
    </div>

    <div class="section">
        <h2>Location Contact</h2>
        <div class="info-grid">
            <div><strong>Address:</strong></div>
            <div>{{ $return->location->address }},
                 {{ $return->location->city }},
                 {{ $return->location->state }}
                 {{ $return->location->postal_code }}</div>

            <div><strong>Phone:</strong></div>
            <div>{{ $return->location->phone }}</div>

            <div><strong>Email:</strong></div>
            <div>{{ $return->location->email }}</div>
        </div>
    </div>
</body>
</html>
