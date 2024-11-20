<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.4;
            color: #333;
        }
        .invoice-header {
            padding: 20px 0;
            display: table;
            width: 100%;
        }
        .invoice-header > div {
            display: table-cell;
            width: 50%;
        }
        .text-right {
            text-align: right;
        }
        .company-info {
            color: #666;
        }
        .client-info {
            padding: 20px 0;
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
        }
        .amount-col {
            text-align: right;
        }
        .totals {
            width: 40%;
            float: right;
            margin: 20px 0;
        }
        .totals table {
            margin: 0;
        }
        .totals table td {
            border: none;
        }
        .notes {
            clear: both;
            padding: 20px 0;
            border-top: 1px solid #ddd;
        }
        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-overdue {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        <div>
            <h1 style="margin: 0; color: #2d3748;">INVOICE</h1>
            <p>Invoice Number: {{ $invoice->invoice_number }}</p>
            <p>Issue Date: {{ $invoice->issue_date->format('M d, Y') }}</p>
            <p>Due Date: {{ $invoice->due_date->format('M d, Y') }}</p>
        </div>
        <div class="text-right company-info">
            <div class="status status-{{ $invoice->status }}">
                {{ ucfirst($invoice->status) }}
            </div>
            <p>Your Company Name</p>
            <p>123 Business Street</p>
            <p>City, State ZIP</p>
            <p>Phone: (123) 456-7890</p>
        </div>
    </div>

    <div class="client-info">
        <h2 style="margin: 0 0 10px 0;">Bill To:</h2>
        <p style="margin: 0;">{{ $invoice->client->name }}</p>
        <p style="margin: 0;">{{ $invoice->client->address }}</p>
        <p style="margin: 0;">{{ $invoice->client->city }}, {{ $invoice->client->state }} {{ $invoice->client->postal_code }}</p>
        <p style="margin: 0;">{{ $invoice->client->country }}</p>
        <p style="margin: 0;">Email: {{ $invoice->client->email }}</p>
        @if($invoice->client->phone)
            <p style="margin: 0;">Phone: {{ $invoice->client->phone }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th class="amount-col">Quantity</th>
                <th class="amount-col">Unit Price</th>
                <th class="amount-col">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="amount-col">{{ number_format($item->quantity, 2) }}</td>
                    <td class="amount-col">${{ number_format($item->unit_price, 2) }}</td>
                    <td class="amount-col">${{ number_format($item->amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td>Subtotal:</td>
                <td class="amount-col">${{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td>Tax:</td>
                <td class="amount-col">${{ number_format($invoice->tax, 2) }}</td>
            </tr>
            <tr style="font-weight: bold;">
                <td>Total:</td>
                <td class="amount-col">${{ number_format($invoice->total, 2) }}</td>
            </tr>
        </table>
    </div>

    @if($invoice->notes)
        <div class="notes">
            <h3 style="margin: 0 0 10px 0;">Notes:</h3>
            <p style="margin: 0;">{{ $invoice->notes }}</p>
        </div>
    @endif
</body>
</html> 