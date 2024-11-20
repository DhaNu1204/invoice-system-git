<div class="item-row border-b border-gray-200 py-4" data-row="{{ $index }}">
    <div class="grid grid-cols-12 gap-4">
        <!-- Product Selection -->
        <div class="col-span-4">
            <label for="items[{{ $index }}][product_id]" class="block text-sm font-medium text-gray-700">Product</label>
            <select name="items[{{ $index }}][product_id]"
                    class="product-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    onchange="updateProductDetails(this, {{ $index }})">
                <option value="">Select Product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}"
                            data-price="{{ $product->unit_price }}"
                            data-description="{{ $product->description }}"
                            {{ (old("items.{$index}.product_id", $item->product_id ?? '') == $product->id) ? 'selected' : '' }}>
                        {{ $product->name }} ({{ $product->code }})
                    </option>
                @endforeach
            </select>
            @error("items.{$index}.product_id")
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div class="col-span-4">
            <label for="items[{{ $index }}][description]" class="block text-sm font-medium text-gray-700">Description</label>
            <input type="text"
                   name="items[{{ $index }}][description]"
                   value="{{ old("items.{$index}.description", $item->description ?? '') }}"
                   class="description-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            @error("items.{$index}.description")
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Quantity -->
        <div class="col-span-1">
            <label for="items[{{ $index }}][quantity]" class="block text-sm font-medium text-gray-700">Qty</label>
            <input type="number"
                   name="items[{{ $index }}][quantity]"
                   value="{{ old("items.{$index}.quantity", $item->quantity ?? '1') }}"
                   min="1"
                   step="0.01"
                   class="quantity mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                   onchange="calculateLineTotal({{ $index }})">
            @error("items.{$index}.quantity")
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Unit Price -->
        <div class="col-span-1">
            <label for="items[{{ $index }}][unit_price]" class="block text-sm font-medium text-gray-700">Price</label>
            <input type="number"
                   name="items[{{ $index }}][unit_price]"
                   value="{{ old("items.{$index}.unit_price", $item->unit_price ?? '0.00') }}"
                   min="0"
                   step="0.01"
                   class="unit-price mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                   onchange="calculateLineTotal({{ $index }})">
            @error("items.{$index}.unit_price")
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Line Total -->
        <div class="col-span-1">
            <label for="items[{{ $index }}][amount]" class="block text-sm font-medium text-gray-700">Total</label>
            <input type="number"
                   name="items[{{ $index }}][amount]"
                   value="{{ old("items.{$index}.amount", $item->amount ?? '0.00') }}"
                   readonly
                   class="line-total mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm sm:text-sm">
        </div>

        <!-- Remove Button -->
        <div class="col-span-1 flex items-end justify-center">
            <button type="button"
                    class="remove-item mt-1 inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                <svg class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateProductDetails(select, index) {
        const option = select.options[select.selectedIndex];
        const row = select.closest('.item-row');
        
        if (option.value) {
            const price = option.dataset.price;
            const description = option.dataset.description;
            
            row.querySelector('.description-input').value = description;
            row.querySelector('.unit-price').value = price;
            
            calculateLineTotal(index);
        }
    }

    function calculateLineTotal(index) {
        const row = document.querySelector(`[data-row="${index}"]`);
        const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
        const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
        const total = quantity * unitPrice;
        
        row.querySelector('.line-total').value = total.toFixed(2);
        calculateInvoiceTotal();
    }

    function calculateInvoiceTotal() {
        let subtotal = 0;
        document.querySelectorAll('.line-total').forEach(input => {
            subtotal += parseFloat(input.value) || 0;
        });

        const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
        const taxAmount = subtotal * (taxRate / 100);
        const total = subtotal + taxAmount;

        document.getElementById('subtotal').value = subtotal.toFixed(2);
        document.getElementById('tax_amount').value = taxAmount.toFixed(2);
        document.getElementById('total_amount').value = total.toFixed(2);
    }

    // Initialize calculations for existing items
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.item-row').forEach((row, index) => {
            calculateLineTotal(index);
        });
    });
</script>
@endpush 