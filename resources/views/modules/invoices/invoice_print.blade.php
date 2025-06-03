<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $invoice->id }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: sans-serif; background: white; color: #000; padding: 40px; }
        h1 { font-size: 24px; margin-bottom: 10px; }
        .header, .footer { margin-bottom: 40px; }
        .client-info, .invoice-meta { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
        .total-row td { font-weight: bold; }
        .print-button { margin-bottom: 20px; }
        @media print {
            .print-button { display: none; }
        }
    </style>
</head>
<body>
    <div class="print-button">
        <button onclick="window.print()">🖨️ Print Invoice</button>
    </div>

    <div class="header">
        <h1>Invoice #{{ $invoice->id }}</h1>
        @if ($invoice->location)
            <div class="invoice-meta">
                <p><strong>Location:</strong> {{ $invoice->location->name }}</p>
                <p>{{ $invoice->location->address }}</p>
                <p>{{ $invoice->location->city }}, {{ $invoice->location->state }} {{ $invoice->location->postal_code }}</p>
                <p>{{ $invoice->location->phone }}</p>
            </div>
        @endif
        <p><strong>Date:</strong> {{ $invoice->created_at->format('F j, Y') }}</p>
    </div>

    <div class="client-info">
        <h2>Bill To:</h2>
        <p>{{ $invoice->client->first_name }} {{ $invoice->client->last_name }}</p>
        <p>{{ $invoice->client->address }}</p>
        <p>{{ $invoice->client->city }}, {{ $invoice->client->state }} {{ $invoice->client->postal_code }}</p>
        <p>{{ $invoice->client->phone }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Price</th>
                <th>Tax</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td>${{ number_format($item->total_price, 2) }}</td>
                    <td>${{ number_format($item->tax_amount, 2) }}</td>
                    <td>${{ number_format($item->total_price + $item->tax_amount, 2) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3" style="text-align: right;">Grand Total</td>
                <td>${{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Thank you for your business!</p>
    </div>
</body>
</html>
