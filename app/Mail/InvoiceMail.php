<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Invoice $invoice,
        public string $message = '',
        public bool $attachPdf = true
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Invoice #{$this->invoice->invoice_number} from Your Company",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invoices.send',
            with: [
                'invoice' => $this->invoice,
                'customMessage' => $this->message,
            ],
        );
    }

    public function attachments(): array
    {
        if (!$this->attachPdf) {
            return [];
        }

        return [
            Attachment::fromData(
                fn () => \PDF::loadView('invoices.pdf.template', ['invoice' => $this->invoice])->output(),
                "invoice-{$this->invoice->invoice_number}.pdf"
            )
        ];
    }
} 