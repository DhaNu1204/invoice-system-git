<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Invoice') }}: {{ $invoice->invoice_number }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('invoices.edit', $invoice) }}" 
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Edit Invoice
                </a>
                <button onclick="window.print()" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Print Invoice
                </button>
            </div>
        </div>
        <div class="flex gap-2">
    <a href="{{ route('invoices.pdf.download', $invoice) }}" 
        class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
        Download PDF
    </a>
    <a href="{{ route('invoices.pdf.view', $invoice) }}" 
        class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded"
        target="_blank">
        View PDF
    </a>
    <!-- ... existing buttons ... -->
    <x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Invoice') }}: {{ $invoice->invoice_number }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('invoices.edit', $invoice) }}" 
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Edit Invoice
                </a>
                <!-- New Email Button -->
                <a href="{{ route('invoices.email.create', $invoice) }}" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Send Email
                </a>
                <button onclick="window.print()" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Print Invoice
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Invoice Header -->
                    <div class="flex justify-between mb-8">
                        <div>
                            <h1 class="text-2xl font-bold mb-2">INVOICE</h1>
                            <p class="text-gray-600">Invoice Number: {{ $invoice->invoice_number }}</p>
                            <p class="text-gray-600">Issue Date: {{ $invoice->issue_date->format('M d, Y') }}</p>
                            <p class="text-gray-600">Due Date: {{ $invoice->due_date->format('M d, Y') }}</p>
                        </div>
                        <div class="text-right">
                            <div class="mb-2">
                                <span class="px-3 py-1 rounded-full text-sm 
                                    {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                       ($invoice->status === 'overdue' ? 'bg-red-100 text-red-800' : 
                                       'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </div>
                            <p class="text-gray-600">Your Company Name</p>
                            <p class="text-gray-600">123 Business Street</p>
                            <p class="text-gray-600">City, State ZIP</p>
                            <p class="text-gray-600">Phone: (123) 456-7890</p>
                        </div>
                    </div>

                    <!-- Client Information -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold mb-2">Bill To:</h2>
                        <div class="pl-4 border-l-4 border-gray-300">
                            <p class="font-semibold">{{ $invoice->client->name }}</p>
                            <p>{{ $invoice->client->address }}</p>
                            <p>{{ $invoice->client->city }}, {{ $invoice->client->state }} {{ $invoice->client->postal_code }}</p>
                            <p>{{ $invoice->client->country }}</p>
                            <p>Email: {{ $invoice->client->email }}</p>
                            @if($invoice->client->phone)
                                <p>Phone: {{ $invoice->client->phone }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Invoice Items -->
                    <div class="mb-8">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Description
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Quantity
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Unit Price
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($invoice->items as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $item->description }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                            {{ number_format($item->quantity, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                            ${{ number_format($item->unit_price, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                            ${{ number_format($item->amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Totals -->
                    <div class="mb-8 flex justify-end">
                        <div class="w-full md:w-1/3">
                            <div class="flex justify-between py-2">
                                <span class="font-semibold">Subtotal:</span>
                                <span>${{ number_format($invoice->subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="font-semibold">Tax:</span>
                                <span>${{ number_format($invoice->tax, 2) }}</span>
                            </div>
                            <div class="flex justify-between py-2 text-lg font-bold border-t border-gray-200">
                                <span>Total:</span>
                                <span>${{ number_format($invoice->total, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($invoice->notes)
                        <div class="mt-8 p-4 bg-gray-50 rounded">
                            <h3 class="font-semibold mb-2">Notes:</h3>
                            <p class="text-gray-600">{{ $invoice->notes }}</p>
                        </div>
                    @endif

                    <!-- Print Styles -->
                    <style>
                        @media print {
                            .bg-white { background-color: white !important; }
                            .shadow-sm { box-shadow: none !important; }
                            .rounded { border-radius: 0 !important; }
                            .hidden { display: none !important; }
                            body { color: black !important; }
                            a { display: none !important; }
                            button { display: none !important; }
                        }
                    </style>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
</div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Invoice Header -->
                    <div class="flex justify-between mb-8">
                        <div>
                            <h1 class="text-2xl font-bold mb-2">INVOICE</h1>
                            <p class="text-gray-600">Invoice Number: {{ $invoice->invoice_number }}</p>
                            <p class="text-gray-600">Issue Date: {{ $invoice->issue_date->format('M d, Y') }}</p>
                            <p class="text-gray-600">Due Date: {{ $invoice->due_date->format('M d, Y') }}</p>
                        </div>
                        <div class="text-right">
                            <div class="mb-2">
                                <span class="px-3 py-1 rounded-full text-sm 
                                    {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                       ($invoice->status === 'overdue' ? 'bg-red-100 text-red-800' : 
                                       'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </div>
                            <p class="text-gray-600">Your Company Name</p>
                            <p class="text-gray-600">123 Business Street</p>
                            <p class="text-gray-600">City, State ZIP</p>
                            <p class="text-gray-600">Phone: (123) 456-7890</p>
                        </div>
                    </div>

                    <!-- Client Information -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold mb-2">Bill To:</h2>
                        <div class="pl-4 border-l-4 border-gray-300">
                            <p class="font-semibold">{{ $invoice->client->name }}</p>
                            <p>{{ $invoice->client->address }}</p>
                            <p>{{ $invoice->client->city }}, {{ $invoice->client->state }} {{ $invoice->client->postal_code }}</p>
                            <p>{{ $invoice->client->country }}</p>
                            <p>Email: {{ $invoice->client->email }}</p>
                            @if($invoice->client->phone)
                                <p>Phone: {{ $invoice->client->phone }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Invoice Items -->
                    <div class="mb-8">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Description
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Quantity
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Unit Price
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($invoice->items as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $item->description }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                            {{ number_format($item->quantity, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                            ${{ number_format($item->unit_price, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                            ${{ number_format($item->amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Totals -->
                    <div class="mb-8 flex justify-end">
                        <div class="w-full md:w-1/3">
                            <div class="flex justify-between py-2">
                                <span class="font-semibold">Subtotal:</span>
                                <span>${{ number_format($invoice->subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="font-semibold">Tax:</span>
                                <span>${{ number_format($invoice->tax, 2) }}</span>
                            </div>
                            <div class="flex justify-between py-2 text-lg font-bold border-t border-gray-200">
                                <span>Total:</span>
                                <span>${{ number_format($invoice->total, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($invoice->notes)
                        <div class="mt-8 p-4 bg-gray-50 rounded">
                            <h3 class="font-semibold mb-2">Notes:</h3>
                            <p class="text-gray-600">{{ $invoice->notes }}</p>
                        </div>
                    @endif

                    <!-- Print Styles -->
                    <style>
                        @media print {
                            .bg-white { background-color: white !important; }
                            .shadow-sm { box-shadow: none !important; }
                            .rounded { border-radius: 0 !important; }
                            .hidden { display: none !important; }
                            body { color: black !important; }
                            a { display: none !important; }
                            button { display: none !important; }
                        }
                    </style>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 