<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function create(Invoice $invoice)
    {
        $paymentMethods = [
            'cash' => 'Cash',
            'bank_transfer' => 'Bank Transfer',
            'credit_card' => 'Credit Card',
            'check' => 'Check',
        ];

        return view('payments.create', compact('invoice', 'paymentMethods'));
    }

    public function store(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', "max:{$invoice->due_amount}"],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['required', 'string'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $payment = $invoice->payments()->create($validated);

        // Update invoice status if fully paid
        $invoice->updatePaymentStatus();

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Payment recorded successfully.');
    }

    public function destroy(Payment $payment)
    {
        $invoice = $payment->invoice;
        $payment->delete();
        
        // Update invoice status
        $invoice->updatePaymentStatus();

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Payment deleted successfully.');
    }
}
