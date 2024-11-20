<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InvoiceEmailController extends Controller
{
    public function create(Invoice $invoice)
    {
        return view('invoices.email.form', compact('invoice'));
    }

    public function send(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'recipient_email' => 'required|email',
            'message' => 'nullable|string',
            'attach_pdf' => 'boolean',
        ]);

        Mail::to($validated['recipient_email'])
            ->send(new InvoiceMail(
                invoice: $invoice,
                message: $validated['message'] ?? '',
                attachPdf: $validated['attach_pdf'] ?? true
            ));

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Invoice has been sent successfully!');
    }
} 