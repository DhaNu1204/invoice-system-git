@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create New Invoice</h1>

    <form action="{{ route('invoices.store') }}" method="POST" id="invoice-form">
        @csrf
        
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="customer_id">Customer</label>
                            <select name="customer_id" id="customer_id" class="form-control" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="invoice_date">Invoice Date</label>
                            <input type="date" name="invoice_date" id="invoice_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="due_date">Due Date</label>
                            <input type="date" name="due_date" id="due_date" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h3>Items</h3>
                <div id="items-container">
                    <!-- Items will be added here dynamically -->
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
                                <td id="subtotal">$0.00</td>
                            </tr>
                            <tr>
                                <th>Tax (10%):</th>
                                <td id="tax">$0.00</td>
                            </tr>
                            <tr>
                                <th>Total:</th>
                                <td id="total">$0.00</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-right">
            <button type="submit" class="btn btn-primary">Create Invoice</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Add first item row if none exists
        if (document.querySelectorAll('.item-row').length === 0) {
            addItem();
        }
        updateTotals();
    });

    // Add new item row
    function addItem() {
        const container = document.getElementById('items-container');
        const itemIndex = container.children.length;
        
        const itemHtml = `
            <div class="row mb-3 item-row">
                <div class="col-md-4">
                    <select name="items[${itemIndex}][product_id]" class="form-control product-select" required onchange="handleProductSelect(this)">
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="items[${itemIndex}][quantity]" 
                           class="form-control quantity" min="1" value="1" 
                           required onchange="calculateLineTotal(this)">
                </div>
                <div class="col-md-2">
                    <input type="number" name="items[${itemIndex}][unit_price]" 
                           class="form-control unit-price" step="0.01" required 
                           onchange="calculateLineTotal(this)">
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control line-total" readonly>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger" onclick="removeItem(this)">Remove</button>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', itemHtml);
    }

    // Remove item row
    function removeItem(button) {
        const row = button.closest('.item-row');
        if (document.querySelectorAll('.item-row').length > 1) {
            row.remove();
            updateTotals();
        } else {
            alert('At least one item is required.');
        }
    }

    // Handle product selection
    function handleProductSelect(select) {
        const row = select.closest('.item-row');
        const selectedOption = select.options[select.selectedIndex];
        const price = selectedOption.dataset.price;
        
        const unitPriceInput = row.querySelector('.unit-price');
        unitPriceInput.value = price;
        
        calculateLineTotal(unitPriceInput);
    }

    // Calculate line total
    function calculateLineTotal(input) {
        const row = input.closest('.item-row');
        const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
        const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
        const lineTotal = quantity * unitPrice;
        
        row.querySelector('.line-total').value = lineTotal.toFixed(2);
        updateTotals();
    }

    // Update all totals
    function updateTotals() {
        let subtotal = 0;
        
        // Calculate subtotal
        document.querySelectorAll('.line-total').forEach(input => {
            subtotal += parseFloat(input.value) || 0;
        });
        
        // Calculate tax and total
        const taxRate = 0.10; // 10%
        const tax = subtotal * taxRate;
        const total = subtotal + tax;
        
        // Update display
        document.getElementById('subtotal').textContent = formatCurrency(subtotal);
        document.getElementById('tax').textContent = formatCurrency(tax);
        document.getElementById('total').textContent = formatCurrency(total);
        
        // Update hidden inputs for form submission
        updateHiddenTotals(subtotal, tax, total);
    }

    // Format currency
    function formatCurrency(amount) {
        return '$' + amount.toFixed(2);
    }

    // Update hidden inputs for form submission
    function updateHiddenTotals(subtotal, tax, total) {
        // Create or update hidden inputs
        let form = document.getElementById('invoice-form');
        
        updateOrCreateHiddenInput(form, 'subtotal', subtotal);
        updateOrCreateHiddenInput(form, 'tax', tax);
        updateOrCreateHiddenInput(form, 'total', total);
    }

    // Helper function to update or create hidden inputs
    function updateOrCreateHiddenInput(form, name, value) {
        let input = form.querySelector(`input[name="${name}"]`);
        if (!input) {
            input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            form.appendChild(input);
        }
        input.value = value.toFixed(2);
    }

    // Form validation before submit
    document.getElementById('invoice-form').addEventListener('submit', function(e) {
        const items = document.querySelectorAll('.item-row');
        let valid = true;

        items.forEach(item => {
            const productSelect = item.querySelector('.product-select');
            const quantity = item.querySelector('.quantity');
            const unitPrice = item.querySelector('.unit-price');

            if (!productSelect.value || !quantity.value || !unitPrice.value) {
                valid = false;
            }
        });

        if (!valid) {
            e.preventDefault();
            alert('Please fill in all item details correctly.');
        }
    });

    // Optional: Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Alt + N to add new item
        if (e.altKey && e.key === 'n') {
            e.preventDefault();
            addItem();
        }
    });

    // Optional: Add auto-save functionality
    let autoSaveTimeout;
    document.getElementById('invoice-form').addEventListener('change', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(function() {
            // Implement auto-save logic here
            console.log('Auto-saving...');
        }, 1000);
    });
</script>
@endpush
@endsection