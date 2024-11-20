<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pay Invoice') }}: {{ $invoice->invoice_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Invoice Summary</h3>
                        <div class="bg-gray-50 p-4 rounded">
                            <div class="flex justify-between mb-2">
                                <span>Invoice Number:</span>
                                <span>{{ $invoice->invoice_number }}</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span>Due Date:</span>
                                <span>{{ $invoice->due_date->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between font-bold">
                                <span>Amount Due:</span>
                                <span>${{ number_format($invoice->total, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <form id="payment-form" class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Card Details</label>
                            <div id="card-element" class="mt-1 p-3 border rounded-md"></div>
                            <div id="card-errors" class="mt-2 text-red-600 text-sm" role="alert"></div>
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('invoices.show', $invoice) }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Pay ${{ number_format($invoice->total, 2) }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ config('services.stripe.key') }}');
        const elements = stripe.elements();
        const card = elements.create('card');
        card.mount('#card-element');

        const form = document.getElementById('payment-form');
        const errorElement = document.getElementById('card-errors');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = 'Processing...';

            try {
                const { paymentIntent, error } = await stripe.confirmCardPayment('{{ $clientSecret }}', {
                    payment_method: {
                        card: card,
                        billing_details: {
                            name: '{{ $invoice->client->name }}',
                            email: '{{ $invoice->client->email }}'
                        }
                    }
                });

                if (error) {
                    errorElement.textContent = error.message;
                    submitButton.disabled = false;
                    submitButton.textContent = 'Pay ${{ number_format($invoice->total, 2) }}';
                } else {
                    // Payment successful
                    window.location.href = '{{ route('invoices.payment.store', $invoice) }}' + 
                        '?payment_intent=' + paymentIntent.id;
                }
            } catch (e) {
                errorElement.textContent = 'An unexpected error occurred.';
                submitButton.disabled = false;
                submitButton.textContent = 'Pay ${{ number_format($invoice->total, 2) }}';
            }
        });
    </script>
    @endpush
</x-app-layout> 