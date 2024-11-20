<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #2d3748;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4299e1;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #edf2f7;
            text-align: center;
            font-size: 14px;
            color: #718096;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Invoice from Your Company</h1>
    </div>

    <p>Dear {{ $invoice->client->name }},</p>

    @if($customMessage)
        <p>{{ $customMessage }}</p>
    @else
        <p>Please find attached invoice #{{ $invoice->invoice_number }} for your recent services.</p>
    @endif

    <p>Invoice Details:</p>
    <ul>
        <li>Invoice Number: {{ $invoice->invoice_number }}</li>
        <li>Issue Date: {{ $invoice->issue_date->format('M d, Y') }}</li>
        <li>Due Date: {{ $invoice->due_date->format('M d, Y') }}</li>
        <li>Amount Due: <span class="amount">${{ number_format($invoice->total, 2) }}</span></li>
    </ul>

    <p>
        <a href="{{ route('invoices.show', $invoice) }}" class="button">View Invoice Online</a>
    </p>

    <p>If you have any questions, please don't hesitate to contact us.</p>

    <p>Thank you for your business!</p>

    <div class="footer">
        <p>Your Company Name<br>
        123 Business Street<br>
        City, State ZIP<br>
        Phone: (123) 456-7890</p>
    </div>
</body>
</html> 