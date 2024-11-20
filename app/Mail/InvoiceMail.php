<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function build()
    {
        return $this->markdown('emails.invoices.send')
                    ->subject("Invoice #{$this->invoice->invoice_number}")
                    ->attach(storage_path("app/invoices/{$this->invoice->invoice_number}.pdf"));
    }
} 