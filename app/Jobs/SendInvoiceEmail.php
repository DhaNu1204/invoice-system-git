<?php

namespace App\Jobs;

use App\Models\Invoice;
use App\Mail\InvoiceMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendInvoiceEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $maxExceptions = 3;
    public $timeout = 60;
    public $backoff = [60, 180, 360]; // Retry after 1, 3, and 6 minutes

    public function __construct(
        protected Invoice $invoice
    ) {}

    public function handle(): void
    {
        Mail::to($this->invoice->customer->email)
            ->send(new InvoiceMail($this->invoice));

        $this->invoice->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        $this->invoice->update([
            'last_send_error' => $exception->getMessage(),
            'last_send_attempt' => now(),
        ]);
        
        // You might want to notify admins about the failure
        \Log::error('Failed to send invoice: ' . $exception->getMessage(), [
            'invoice_id' => $this->invoice->id,
            'customer_id' => $this->invoice->customer_id,
        ]);
    }
}
