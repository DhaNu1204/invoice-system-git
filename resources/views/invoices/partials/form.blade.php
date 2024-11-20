<div class="space-y-12">
    <div class="border-b border-gray-900/10 pb-12">
        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
            <!-- Customer Selection -->
            <div class="sm:col-span-3">
                <label for="customer_id" class="block text-sm font-medium leading-6 text-gray-900">Customer</label>
                <div class="mt-2">
                    <select name="customer_id" 
                            id="customer_id" 
                            required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" 
                                {{ (old('customer_id', $invoice->customer_id ?? '') == $customer->id) ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('customer_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Invoice Number -->
            <div class="sm:col-span-3">
                <label for="invoice_number" class="block text-sm font-medium leading-6 text-gray-900">Invoice Number</label>
                <div class="mt-2">
                    <input type="text" 
                           name="invoice_number" 
                           id="invoice_number" 
                           value="{{ old('invoice_number', $invoice->invoice_number ?? $nextInvoiceNumber) }}"
                           readonly
                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 bg-gray-50">
                </div>
            </div>

            <!-- Issue Date -->
            <div class="sm:col-span-3">
                <label for="issue_date" class="block text-sm font-medium leading-6 text-gray-900">Issue Date</label>
                <div class="mt-2">
                    <input type="date" 
                           name="issue_date" 
                           id="issue_date" 
                           value="{{ old('issue_date', $invoice->issue_date ?? today()->format('Y-m-d')) }}"
                           required
                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
                @error('issue_date')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Due Date -->
            <div class="sm:col-span-3">
                <label for="due_date" class="block text-sm font-medium leading-6 text-gray-900">Due Date</label>
                <div class="mt-2">
                    <input type="date" 
                           name="due_date" 
                           id="due_date" 
                           value="{{ old('due_date', $invoice->due_date ?? today()->addDays(30)->format('Y-m-d')) }}"
                           required
                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
                @error('due_date')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Invoice Items -->
    <div class="border-b border-gray-900/10 pb-12">
        <h2 class="text-base font-semibold leading-7 text-gray-900">Invoice Items</h2>
        
        <div id="items-container">
            @forelse(old('items', $invoice->items ?? []) as $index => $item)
                @include('invoices.partials.item-row', [
                    'index' => $index,
                    'item' => $item,
                    'products' => $products
                ])
            @empty
                @include('invoices.partials.item-row', [
                    'index' => 0,
                    'item' => null,
                    'products' => $products
                ])
            @endforelse
        </div>

        <button type="button" 
                id="add-item"
                class="mt-4 rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500">
            Add Item
        </button>
    </div>

    <!-- Totals -->
    <div class="border-b border-gray-900/10 pb-12">
        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
            <div class="sm:col-span-3 sm:col-start-4">
                <div class="space-y-4">
                    <!-- Subtotal -->
                    <div class="flex justify-between">
                        <label for="subtotal" class="block text-sm font-medium leading-6 text-gray-900">Subtotal</label>
                        <div class="mt-2">
                            <input type="text" 
                                   name="subtotal" 
                                   id="subtotal" 
                                   value="{{ old('subtotal', $invoice->subtotal ?? '0.00') }}"
                                   readonly
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 bg-gray-50">
                        </div>
                    </div>

                    <!-- Tax Rate -->
                    <div class="flex justify-between">
                        <label for="tax_rate" class="block text-sm font-medium leading-6 text-gray-900">Tax Rate (%)</label>
                        <div class="mt-2">
                            <input type="number" 
                                   name="tax_rate" 
                                   id="tax_rate" 
                                   value="{{ old('tax_rate', $invoice->tax_rate ?? $defaultTaxRate) }}"
                                   step="0.01"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <!-- Tax Amount -->
                    <div class="flex justify-between">
                        <label for="tax_amount" class="block text-sm font-medium leading-6 text-gray-900">Tax Amount</label>
                        <div class="mt-2">
                            <input type="text" 
                                   name="tax_amount" 
                                   id="tax_amount" 
                                   value="{{ old('tax_amount', $invoice->tax_amount ?? '0.00') }}"
                                   readonly
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 bg-gray-50">
                        </div>
                    </div>

                    <!-- Total Amount -->
                    <div class="flex justify-between">
                        <label for="total_amount" class="block text-sm font-medium leading-6 text-gray-900">Total Amount</label>
                        <div class="mt-2">
                            <input type="text" 
                                   name="total_amount" 
                                   id="total_amount" 
                                   value="{{ old('total_amount', $invoice->total_amount ?? '0.00') }}"
                                   readonly
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 bg-gray-50">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes -->
    <div class="border-b border-gray-900/10 pb-12">
        <div class="mt-10">
            <label for="notes" class="block text-sm font-medium leading-6 text-gray-900">Notes</label>
            <div class="mt-2">
                <textarea name="notes" 
                          id="notes" 
                          rows="3" 
                          class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">{{ old('notes', $invoice->notes ?? '') }}</textarea>
            </div>
        </div>
    </div>
</div>

<!-- Template for new items -->
<template id="item-template">
    @include('invoices.partials.item-row', [
        'index' => 'INDEX',
        'item' => null,
        'products' => $products
    ])
</template> 