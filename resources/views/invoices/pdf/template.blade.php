<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
        }
        .invoice-header {
            padding: 20px 0;
            border-bottom: 2px solid #ddd;
            margin-bottom: 20px;
        }
        .company-info {
            float: left;
        }
        .invoice-info {
            float: right;
            text-align: right;
        }
        .customer-info {
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        .totals {
            float: right;
            width: 300px;
        }
        .totals td {
            text-align: right;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        <div class="company-info">
            <h1>{{ config('app.name') }}</h1>
            <p>Your Company Address<br>
               Phone: +1 234 567 8901<br>
               Email: company@example.com</p>
        </div>
        <div class="invoice-info">
            <h2>INVOICE</h2>
            <p>Invoice #: {{ $invoice->invoice_number }}<br>
               Date: {{ $invoice->invoice_date->format('M d, Y') }}<br>
               Due Date: {{ $invoice->due_date->format('M d, Y') }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="customer-info">
        <h3>Bill To:</h3>
        <p>{{ $invoice->customer->name }}<br>
           {{ $invoice->customer->address }}<br>
           {{ $invoice->customer->email }}<br>
           {{ $invoice->customer->phone }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>${{ number_format($item->unit_price, 2) }}</td>
                <td>${{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td>Subtotal:</td>
                <td>${{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td>Tax (10%):</td>
                <td>${{ number_format($invoice->tax, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Total:</strong></td>
                <td><strong>${{ number_format($invoice->total, 2) }}</strong></td>
            </tr>
        </table>
    </div>

    <div style="clear: both;"></div>

    <div class="footer">
        <p>Thank you for your business!</p>
    </div>
</body>
</html> 