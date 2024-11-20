<x-mail::message>
# Invoice #{{ $invoice->invoice_number }}

Dear {{ $customer->name }},

Please find attached your invoice for {{ config('app.name') }}.

**Invoice Details:**
- Invoice Number: {{ $invoice->invoice_number }}
- Issue Date: {{ $invoice->issue_date->format('F d, Y') }}
- Due Date: {{ $invoice->due_date->format('F d, Y') }}
- Amount Due: ${{ number_format($invoice->total_amount, 2) }}

<x-mail::button :url="route('invoices.show', $invoice)">
View Invoice Online
</x-mail::button>

Thank you for your business!

Regards,<br>
{{ config('app.name') }}
</x-mail::message> 