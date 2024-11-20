<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Invoice') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('invoices.store') }}" method="POST" id="invoice-form">
                        @csrf
                        
                        <!-- Client Selection -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="client_id">
                                Client
                            </label>
                            <select name="client_id" id="client_id" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Select Client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Invoice Details -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="invoice_number">
                                    Invoice Number
                                </label>
                                <input type="text" name="invoice_number" id="invoice_number" 
                                    value="{{ old('invoice_number') }}" required
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                @error('invoice_number')
                                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="issue_date">
                                    Issue Date
                                </label>
                                <input type="date" name="issue_date" id="issue_date" 
                                    value="{{ old('issue_date', date('Y-m-d')) }}" required
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                @error('issue_date')
                                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="due_date">
                                    Due Date
                                </label>
                                <input type="date" name="due_date" id="due_date" 
                                    value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" required
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                @error('due_date')
                                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Invoice Items -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Invoice Items
                            </label>
                            <div id="invoice-items">
                                <!-- Invoice items will be added here -->
                            </div>
                            <button type="button" onclick="addInvoiceItem()"
                                class="mt-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Add Item
                            </button>
                        </div>

                        <!-- Totals -->
                        <div class="mb-4 flex flex-col items-end">
                            <div class="w-full md:w-1/3">
                                <div class="flex justify-between mb-2">
                                    <span class="font-bold">Subtotal:</span>
                                    <span id="subtotal">$0.00</span>
                                    <input type="hidden" name="subtotal" id="subtotal-input" value="0">
                                </div>
                                <div class="flex justify-between mb-2">
                                    <span class="font-bold">Tax (%):</span>
                                    <input type="number" name="tax_rate" id="tax-rate" value="{{ old('tax_rate', 0) }}"
                                        class="w-20 shadow appearance-none border rounded py-1 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                        onchange="calculateTotals()">
                                    <span id="tax-amount">$0.00</span>
                                    <input type="hidden" name="tax" id="tax-input" value="0">
                                </div>
                                <div class="flex justify-between font-bold">
                                    <span>Total:</span>
                                    <span id="total">$0.00</span>
                                    <input type="hidden" name="total" id="total-input" value="0">
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="notes">
                                Notes
                            </label>
                            <textarea name="notes" id="notes" rows="3"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('notes') }}</textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('invoices.index') }}" 
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Cancel
                            </a>
                            <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Create Invoice
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let itemCount = 0;

        function addInvoiceItem() {
            const itemHtml = `
                <div class="invoice-item bg-gray-50 p-4 mb-2 rounded" id="item-${itemCount}">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="col-span-2">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                            <input type="text" name="items[${itemCount}][description]" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Quantity</label>
                            <input type="number" name="items[${itemCount}][quantity]" step="0.01" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                onchange="calculateItemAmount(${itemCount})">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Unit Price</label>
                            <input type="number" name="items[${itemCount}][unit_price]" step="0.01" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                onchange="calculateItemAmount(${itemCount})">
                        </div>
                        <div class="col-span-3">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Amount</label>
                            <input type="number" name="items[${itemCount}][amount]" readonly
                                class="bg-gray-100 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight">
                        </div>
                        <div class="flex items-end">
                            <button type="button" onclick="removeInvoiceItem(${itemCount})"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Remove
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('invoice-items').insertAdjacentHTML('beforeend', itemHtml);
            itemCount++;
        }

        function removeInvoiceItem(id) {
            document.getElementById(`item-${id}`).remove();
            calculateTotals();
        }

        function calculateItemAmount(id) {
            const item = document.getElementById(`item-${id}`);
            const quantity = parseFloat(item.querySelector('input[name^="items"][name$="[quantity]"]').value) || 0;
            const unitPrice = parseFloat(item.querySelector('input[name^="items"][name$="[unit_price]"]').value) || 0;
            const amount = quantity * unitPrice;
            item.querySelector('input[name^="items"][name$="[amount]"]').value = amount.toFixed(2);
            calculateTotals();
        }

        function calculateTotals() {
            let subtotal = 0;
            document.querySelectorAll('input[name^="items"][name$="[amount]"]').forEach(input => {
                subtotal += parseFloat(input.value) || 0;
            });

            const taxRate = parseFloat(document.getElementById('tax-rate').value) || 0;
            const taxAmount = subtotal * (taxRate / 100);
            const total = subtotal + taxAmount;

            document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
            document.getElementById('subtotal-input').value = subtotal.toFixed(2);
            document.getElementById('tax-amount').textContent = `$${taxAmount.toFixed(2)}`;
            document.getElementById('tax-input').value = taxAmount.toFixed(2);
            document.getElementById('total').textContent = `$${total.toFixed(2)}`;
            document.getElementById('total-input').value = total.toFixed(2);
        }

        // Add first item by default
        document.addEventListener('DOMContentLoaded', function() {
            addInvoiceItem();
        });
    </script>
    @endpush
</x-app-layout>