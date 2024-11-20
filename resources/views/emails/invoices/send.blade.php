@component('mail::message')
# Invoice from Your Company

Dear {{ $invoice->customer->name }},

Your invoice #{{ $invoice->invoice_number }} has been generated.

@component('mail::button', ['url' => $downloadUrl])
Download Invoice
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent 