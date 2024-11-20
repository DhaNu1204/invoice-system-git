<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Product;
use App\Http\Requests\InvoiceRequest;
use Illuminate\Http\Request;
use PDF;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('customer')
            ->latest()
            ->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('invoices.create', compact('customers', 'products'));
    }

    public function store(InvoiceRequest $request)
    {
        \DB::transaction(function () use ($request) {
            $invoice = Invoice::create([
                'customer_id' => $request->customer_id,
                'invoice_number' => 'INV-' . time(), // You might want a better number generation system
                'invoice_date' => $request->invoice_date,
                'due_date' => $request->due_date,
                'status' => 'draft',
            ]);

            foreach ($request->items as $item) {
                $invoice->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                ]);
            }

            $invoice->calculateTotal();
        });

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'items.product', 'payments']);
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('invoices.edit', compact('invoice', 'customers', 'products'));
    }

    public function download(Invoice $invoice)
    {
        $pdf = PDF::loadView('invoices.pdf.template', compact('invoice'));
        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }

    public function send(Invoice $invoice)
    {
        // Send email logic here
        Mail::to($invoice->customer->email)
            ->send(new InvoiceMail($invoice));

        $invoice->update(['status' => 'sent']);

        return back()->with('success', 'Invoice sent successfully.');
    }
} 