<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class InvoicePaymentController extends Controller
{
    public function create(Invoice $invoice)
    {
        // Don't allow payment for paid invoices
        if ($invoice->status === 'paid') {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'This invoice has already been paid.');
        }

        // Set Stripe API key
        Stripe::setApiKey(config('services.stripe.secret'));

        // Create payment intent
        $intent = PaymentIntent::create([
            'amount' => $invoice->total * 100, // Amount in cents
            'currency' => 'usd',
            'metadata' => [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
            ],
        ]);

        return view('invoices.payment.form', [
            'invoice' => $invoice,
            'clientSecret' => $intent->client_secret,
        ]);
    }

    public function store(Request $request, Invoice $invoice)
    {
        // Verify payment status with Stripe
        Stripe::setApiKey(config('services.stripe.secret'));
        $intent = PaymentIntent::retrieve($request->payment_intent);

        if ($intent->status === 'succeeded') {
            // Update invoice status
            $invoice->update([
                'status' => 'paid',
                'paid_at' => now(),
                'payment_reference' => $request->payment_intent,
            ]);

            return redirect()->route('invoices.payment.success', $invoice);
        }

        return redirect()->route('invoices.show', $invoice)
            ->with('error', 'Payment failed. Please try again.');
    }

    public function success(Invoice $invoice)
    {
        if ($invoice->status !== 'paid') {
            return redirect()->route('invoices.show', $invoice);
        }

        return view('invoices.payment.success', compact('invoice'));
    }
}