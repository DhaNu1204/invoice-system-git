@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Invoices</h1>
        <a href="{{ route('invoices.create') }}" class="btn btn-primary">Create New Invoice</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Due Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>{{ $invoice->customer->name }}</td>
                        <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                        <td>{{ $invoice->due_date->format('Y-m-d') }}</td>
                        <td>${{ number_format($invoice->total, 2) }}</td>
                        <td>{{ ucfirst($invoice->status) }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-sm btn-warning">Edit</a>
                                <a href="{{ route('invoices.download', $invoice) }}" class="btn btn-sm btn-secondary">Download</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $invoices->links() }}
        </div>
    </div>
</div>
@endsection 