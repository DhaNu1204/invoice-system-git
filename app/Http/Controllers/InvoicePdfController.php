<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoicePdfController extends Controller
{
    public function download(Invoice $invoice)
    {
        $pdf = PDF::loadView('invoices.pdf.template', compact('invoice'));
        
        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }

    public function stream(Invoice $invoice)
    {
        $pdf = PDF::loadView('invoices.pdf.template', compact('invoice'));
        
        return $pdf->stream("invoice-{$invoice->invoice_number}.pdf");
    }
}