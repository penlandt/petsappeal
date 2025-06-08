<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $invoice->id }}</title>
    <style>
        body { font-family: sans-serif; background: white; color: #000; padding: 20px; }
        h1 { font-size: 20px; margin-bottom: 10px; }
        .logo img { max-height: 80px; margin-bottom: 15px; }
        .section { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f9f9f9; }
        .total-row td { font-weight: bold; }
    </style>
</head>
<body>
    <div class="logo">
        <img src="{{ url('/company-assets/logo/' . $invoice->location->company_id) }}" alt="Company Logo">
    </div>

    <h1>Invoice #{{ $invoice->id }}</h1>

    <div class="section">
        <p><strong>Date:</strong> {{ $invoice->created_at->format('F j, Y') }}</p>
        @if ($invoice->location)
            <p><strong>Location:</strong> {{ $invoice->location->name }}<br>
            {{ $invoice->location->address }}<br>
            {{ $invoice->location->city }}, {{ $invoice->location->state }} {{ $invoice->location->postal_code }}<br>
            {{ $invoice->location->phone }}</p>
        @endif
    </div>

    <div class="section">
        <h2>Bill To:</h2>
        <p>{{ $invoice->client->first_name }} {{ $invoice->client->last_name }}<br>
        {{ $invoice->client->address }}<br>
        {{ $invoice->client->city }}, {{ $invoice->client->state }} {{ $invoice->client->postal_code }}<br>
        {{ $invoice->client->phone }}</p>
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

    <p>Thank you for your business!</p>
</body>
</html>
