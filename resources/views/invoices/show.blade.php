@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Invoice #{{ $invoice->invoice_number }}</h2>
            <div class="btn-group">
                <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-warning">Edit</a>
                <a href="{{ route('invoices.pdf', $invoice) }}" class="btn btn-secondary">Download PDF</a>
                @if($invoice->status === 'draft')
                    <form action="{{ route('invoices.send', $invoice) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary">Send to Customer</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-sm-6">
                    <h6 class="mb-3">From:</h6>
                    <div>
                        <strong>{{ config('app.name') }}</strong>
                    </div>
                    <div>Your Company Address</div>
                    <div>Email: company@example.com</div>
                    <div>Phone: +1 234 567 8901</div>
                </div>

                <div class="col-sm-6">
                    <h6 class="mb-3">To:</h6>
                    <div>
                        <strong>{{ $invoice->customer->name }}</strong>
                    </div>
                    <div>{{ $invoice->customer->address }}</div>
                    <div>Email: {{ $invoice->customer->email }}</div>
                    <div>Phone: {{ $invoice->customer->phone }}</div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-sm-6">
                    <div><strong>Invoice Date:</strong> {{ $invoice->invoice_date->format('M d, Y') }}</div>
                    <div><strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}</div>
                </div>
                <div class="col-sm-6">
                    <div><strong>Status:</strong> 
                        <span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : 'warning' }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-right">${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right"><strong>Subtotal</strong></td>
                            <td class="text-right">${{ number_format($invoice->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-right"><strong>Tax (10%)</strong></td>
                            <td class="text-right">${{ number_format($invoice->tax, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-right"><strong>Total</strong></td>
                            <td class="text-right"><strong>${{ number_format($invoice->total, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if($invoice->payments->count() > 0)
            <div class="mt-4">
                <h4>Payment History</h4>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Transaction ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                            <td>${{ number_format($payment->amount, 2) }}</td>
                            <td>{{ ucfirst($payment->payment_method) }}</td>
                            <td>{{ $payment->transaction_id }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 