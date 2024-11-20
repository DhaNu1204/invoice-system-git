<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Setting;
use App\Http\Requests\InvoiceRequest;
use Illuminate\Http\Request;
use Dompdf\Dompdf;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['customer'])
            ->latest()
            ->paginate(10);
            
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::where('is_active', true)->get();
        $defaultTaxRate = Setting::getSetting('default_tax_rate', 0);
        $nextInvoiceNumber = $this->generateNextInvoiceNumber();
        
        return view('invoices.create', compact(
            'customers', 
            'products', 
            'defaultTaxRate',
            'nextInvoiceNumber'
        ));
    }

    public function store(InvoiceRequest $request)
    {
        $validated = $request->validated();
        
        $invoice = Invoice::create([
            'customer_id' => $validated['customer_id'],
            'invoice_number' => $validated['invoice_number'],
            'issue_date' => $validated['issue_date'],
            'due_date' => $validated['due_date'],
            'tax_rate' => $validated['tax_rate'],
            'notes' => $validated['notes'] ?? null,
            'status' => $request->input('status', 'draft'),
        ]);

        foreach ($validated['items'] as $item) {
            $invoice->items()->create($item);
        }

        // If status is 'sent', send the invoice email
        if ($request->input('status') === 'sent') {
            // Add invoice sending logic here
        }

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'items.product', 'payments']);
        $settings = Setting::first();
        
        return view('invoices.show', compact('invoice', 'settings'));
    }

    public function edit(Invoice $invoice)
    {
        if ($invoice->status !== 'draft') {
            return redirect()
                ->route('invoices.show', $invoice)
                ->with('error', 'Only draft invoices can be edited.');
        }

        $customers = Customer::all();
        $products = Product::where('is_active', true)->get();
        $defaultTaxRate = Setting::getSetting('default_tax_rate', 0);
        
        return view('invoices.edit', compact(
            'invoice',
            'customers', 
            'products', 
            'defaultTaxRate'
        ));
    }

    public function update(InvoiceRequest $request, Invoice $invoice)
    {
        if ($invoice->status !== 'draft') {
            return redirect()
                ->route('invoices.show', $invoice)
                ->with('error', 'Only draft invoices can be edited.');
        }

        $validated = $request->validated();
        
        $invoice->update([
            'customer_id' => $validated['customer_id'],
            'invoice_number' => $validated['invoice_number'],
            'issue_date' => $validated['issue_date'],
            'due_date' => $validated['due_date'],
            'tax_rate' => $validated['tax_rate'],
            'notes' => $validated['notes'] ?? null,
            'status' => $request->input('status', 'draft'),
        ]);

        // Delete existing items and create new ones
        $invoice->items()->delete();
        foreach ($validated['items'] as $item) {
            $invoice->items()->create($item);
        }

        // If status is 'sent', send the invoice email
        if ($request->input('status') === 'sent') {
            // Add invoice sending logic here
        }

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice)
    {
        if ($invoice->status !== 'draft') {
            return redirect()
                ->route('invoices.show', $invoice)
                ->with('error', 'Only draft invoices can be deleted.');
        }

        $invoice->items()->delete();
        $invoice->delete();

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    public function send(Invoice $invoice)
    {
        try {
            $invoice->sendInvoice();
            return redirect()
                ->route('invoices.show', $invoice)
                ->with('success', 'Invoice sent successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('invoices.show', $invoice)
                ->with('error', 'Could not send invoice. Please try again.');
        }
    }

    public function downloadPdf(Invoice $invoice)
    {
        $settings = Setting::first();
        $pdf = PDF::loadView('invoices.pdf', compact('invoice', 'settings'));
        
        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }

    private function generateNextInvoiceNumber()
    {
        $lastInvoice = Invoice::latest()->first();
        $prefix = 'INV-';
        $nextNumber = $lastInvoice ? (int)substr($lastInvoice->invoice_number, 4) + 1 : 1;
        
        return $prefix . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }
} 