<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Invoice') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('invoices.store') }}" method="POST" id="invoice-form">
                        @csrf
                        @include('invoices.partials.form')
                        
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <a href="{{ route('invoices.index') }}" class="text-sm font-semibold leading-6 text-gray-900">
                                Cancel
                            </a>
                            <button type="submit" name="status" value="draft" class="rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500">
                                Save as Draft
                            </button>
                            <button type="submit" name="status" value="sent" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                Save and Send
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Invoice item handling JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            let itemCount = 1;
            
            // Add new item row
            document.getElementById('add-item').addEventListener('click', function() {
                itemCount++;
                const template = document.getElementById('item-template').content.cloneNode(true);
                template.querySelector('[data-row]').dataset.row = itemCount;
                document.getElementById('items-container').appendChild(template);
                updateTotals();
            });

            // Remove item row
            document.addEventListener('click', function(e) {
                if (e.target.matches('.remove-item')) {
                    e.preventDefault();
                    e.target.closest('[data-row]').remove();
                    updateTotals();
                }
            });

            // Calculate line totals
            document.addEventListener('input', function(e) {
                if (e.target.matches('.quantity, .unit-price')) {
                    const row = e.target.closest('[data-row]');
                    const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
                    const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
                    row.querySelector('.line-total').value = (quantity * unitPrice).toFixed(2);
                    updateTotals();
                }
            });

            // Update invoice totals
            function updateTotals() {
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
        });
    </script>
    @endpush
</x-app-layout>