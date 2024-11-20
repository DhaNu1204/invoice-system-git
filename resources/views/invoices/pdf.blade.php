<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 14px;
            line-height: 1.4;
            color: #333;
        }

        /* Layout */
        .container {
            padding: 40px;
        }

        .header {
            margin-bottom: 40px;
        }

        .logo {
            max-width: 200px;
            margin-bottom: 20px;
        }

        /* Grid */
        .grid {
            display: block;
            clear: both;
        }

        .col {
            float: left;
            width: 50%;
            margin-bottom: 20px;
        }

        /* Typography */
        h1 {
            font-size: 28px;
            color: #2d3748;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 18px;
            color: #4a5568;
            margin-bottom: 10px;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #2d3748;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th {
            background-color: #f7fafc;
            text-align: left;
            padding: 12px;
            font-weight: bold;
            color: #4a5568;
            border-bottom: 2px solid #e2e8f0;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        .text-right {
            text-align: right;
        }

        /* Status */
        .status {
            padding: 6px 12px;
            border-radius: 4px;
            display: inline-block;
            font-weight: bold;
        }

        .status-paid {
            background-color: #c6f6d5;
            color: #276749;
        }

        .status-pending {
            background-color: #fefcbf;
            color: #975a16;
        }

        .status-overdue {
            background-color: #fed7d7;
            color: #9b2c2c;
        }

        /* Totals */
        .totals {
            float: right;
            width: 300px;
            margin-top: 20px;
        }

        .totals-row {
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .totals-row.final {
            border-top: 2px solid #4a5568;
            border-bottom: none;
            font-weight: bold;
            font-size: 16px;
        }

        /* Footer */
        .footer {
            margin-top: 60px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #718096;
            font-size: 12px;
        }

        /* Utilities */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            @if($settings->logo_path)
                <img src="{{ public_path($settings->logo_path) }}" class="logo" alt="Company Logo">
            @endif
            <h1>INVOICE</h1>
        </div>

        <!-- Invoice Info -->
        <div class="grid clearfix">
            <div class="col">
                <!-- Company Info -->
                <div class="company-name">{{ $settings->company_name }}</div>
                <div>{{ $settings->company_address }}</div>
                <div>{{ $settings->company_email }}</div>
                <div>{{ $settings->company_phone }}</div>
                @if($settings->tax_number)
                    <div>Tax Number: {{ $settings->tax_number }}</div>
                @endif
            </div>
            <div class="col">
                <!-- Invoice Details -->
                <table>
                    <tr>
                        <td><strong>Invoice Number:</strong></td>
                        <td>{{ $invoice->invoice_number }}</td>
                    </tr>
                    <tr>
                        <td><strong>Issue Date:</strong></td>
                        <td>{{ $invoice->issue_date->format('F d, Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Due Date:</strong></td>
                        <td>{{ $invoice->due_date->format('F d, Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            <span class="status status-{{ $invoice->status }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Bill To -->
        <div class="grid clearfix">
            <div class="col">
                <h2>Bill To</h2>
                <div><strong>{{ $invoice->customer->name }}</strong></div>
                @if($invoice->customer->company_name)
                    <div>{{ $invoice->customer->company_name }}</div>
                @endif
                <div>{{ $invoice->customer->address }}</div>
                <div>{{ $invoice->customer->city }}, {{ $invoice->customer->state }} {{ $invoice->customer->postal_code }}</div>
                <div>{{ $invoice->customer->country }}</div>
                <div>{{ $invoice->customer->email }}</div>
                <div>{{ $invoice->customer->phone }}</div>
            </div>
        </div>

        <!-- Items -->
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                    <tr>
                        <td>
                            {{ $item->description }}
                            @if($item->product)
                                <br>
                                <small>{{ $item->product->code }}</small>
                            @endif
                        </td>
                        <td class="text-right">{{ number_format($item->quantity, 2) }}</td>
                        <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right">${{ number_format($item->amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <div class="totals-row">
                <div class="grid clearfix">
                    <div class="col">Subtotal</div>
                    <div class="col text-right">${{ number_format($invoice->subtotal, 2) }}</div>
                </div>
            </div>
            <div class="totals-row">
                <div class="grid clearfix">
                    <div class="col">Tax ({{ $invoice->tax_rate }}%)</div>
                    <div class="col text-right">${{ number_format($invoice->tax_amount, 2) }}</div>
                </div>
            </div>
            <div class="totals-row final">
                <div class="grid clearfix">
                    <div class="col">Total</div>
                    <div class="col text-right">${{ number_format($invoice->total_amount, 2) }}</div>
                </div>
            </div>
            @if($invoice->paid_amount > 0)
                <div class="totals-row">
                    <div class="grid clearfix">
                        <div class="col">Paid</div>
                        <div class="col text-right">${{ number_format($invoice->paid_amount, 2) }}</div>
                    </div>
                </div>
                <div class="totals-row">
                    <div class="grid clearfix">
                        <div class="col">Balance Due</div>
                        <div class="col text-right">${{ number_format($invoice->due_amount, 2) }}</div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Notes -->
        @if($invoice->notes)
            <div style="clear: both; padding-top: 40px;">
                <h2>Notes</h2>
                <p>{{ $invoice->notes }}</p>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>{{ $settings->invoice_footer_text ?? 'Thank you for your business!' }}</p>
        </div>
    </div>
</body>
</html> 