@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Invoice #{{ $invoice->invoice_number }}</h1>

    <form action="{{ route('invoices.update', $invoice) }}" method="POST" id="invoice-form">
        @csrf
        @method('PUT')
        
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="customer_id">Customer</label>
                            <select name="customer_id" id="customer_id" class="form-control" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" 
                                        {{ $invoice->customer_id == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="invoice_date">Invoice Date</label>
                            <input type="date" name="invoice_date" id="invoice_date" 
                                   class="form-control" required 
                                   value="{{ $invoice->invoice_date->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="due_date">Due Date</label>
                            <input type="date" name="due_date" id="due_date" 
                                   class="form-control" required 
                                   value="{{ $invoice->due_date->format('Y-m-d') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h3>Items</h3>
                <div id="items-container">
                    @foreach($invoice->items as $index => $item)
                    <div class="row mb-3 item-row">
                        <div class="col-md-4">
                            <select name="items[{{ $index }}][product_id]" class="form-control product-select" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                            data-price="{{ $product->price }}"
                                            {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="items[{{ $index }}][quantity]" 
                                   class="form-control quantity" min="1" 
                                   value="{{ $item->quantity }}" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="items[{{ $index }}][unit_price]" 
                                   class="form-control unit-price" step="0.01" 
                                   value="{{ $item->unit_price }}" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control line-total" 
                                   value="{{ $item->subtotal }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger" onclick="removeItem(this)">Remove</button>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-secondary" onclick="addItem()">Add Item</button>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="row justify-content-end">
                    <div class="col-md-4">
                        <table class="table">
                            <tr>
                                <th>Subtotal:</th>
                                <td id="subtotal">${{ number_format($invoice->subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Tax (10%):</th>
                                <td id="tax">${{ number_format($invoice->tax, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Total:</th>
                                <td id="total">${{ number_format($invoice->total, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-right">
            <button type="submit" class="btn btn-primary">Update Invoice</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Add the same JavaScript functions as in create.blade.php
    // Plus any additional edit-specific functionality
</script>
@endpush
@endsection