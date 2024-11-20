<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment Successful') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="text-center">
                        <div class="mb-4">
                            <svg class="mx-auto h-12 w-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            Payment Successful!
                        </h3>
                        
                        <p class="text-gray-600 mb-6">
                            Thank you for your payment. Invoice #{{ $invoice->invoice_number }} has been marked as paid.
                        </p>

                        <div class="bg-gray-50 p-4 rounded mb-6 inline-block">
                            <div class="text-left">
                                <div class="mb-2">
                                    <span class="font-semibold">Amount Paid:</span>
                                    <span>${{ number_format($invoice->total, 2) }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Payment Date:</span>
                                    <span>{{ $invoice->paid_at->format('M d, Y H:i:s') }}</span>
                                </div>
                                <div>
                                    <span class="font-semibold">Reference:</span>
                                    <span class="text-sm">{{ $invoice->payment_reference }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-x-4">
                            <a href="{{ route('invoices.show', $invoice) }}"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                View Invoice
                            </a>
                            <a href="{{ route('invoices.index') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to Invoices
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 