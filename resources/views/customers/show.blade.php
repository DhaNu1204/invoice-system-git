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
            <a href="{{ route('invoices.email.create', $invoice) }}" 
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Send Email
            </a>
            <!-- Add the Pay Now button here -->
            @if($invoice->status !== 'paid')
                <a href="{{ route('invoices.payment.create', $invoice) }}" 
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Pay Now
                </a>
            @endif
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
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2 md:col-span-1">
                            <h3 class="text-lg font-semibold mb-4">Client Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold">Name</label>
                                    <p class="text-gray-600">{{ $client->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold">Email</label>
                                    <p class="text-gray-600">{{ $client->email }}</p>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold">Phone</label>
                                    <p class="text-gray-600">{{ $client->phone ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-2 md:col-span-1">
                            <h3 class="text-lg font-semibold mb-4">Address Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold">Address</label>
                                    <p class="text-gray-600">{{ $client->address }}</p>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold">City</label>
                                    <p class="text-gray-600">{{ $client->city }}</p>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold">State</label>
                                    <p class="text-gray-600">{{ $client->state ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold">Country</label>
                                    <p class="text-gray-600">{{ $client->country }}</p>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold">Postal Code</label>
                                    <p class="text-gray-600">{{ $client->postal_code ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Invoices Section -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4">Client Invoices</h3>
                        @if($client->invoices->count() > 0)
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 tracking-wider">Invoice #</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 tracking-wider">Date</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 tracking-wider">Amount</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 tracking-wider">Status</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($client->invoices as $invoice)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
                                                {{ $invoice->invoice_number }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
                                                {{ $invoice->issue_date->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
                                                ${{ number_format($invoice->total, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                                       ($invoice->status === 'overdue' ? 'bg-red-100 text-red-800' : 
                                                       'bg-yellow-100 text-yellow-800') }}">
                                                    {{ ucfirst($invoice->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
                                                <a href="{{ route('invoices.show', $invoice) }}" 
                                                    class="text-blue-600 hover:text-blue-900">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-gray-500">No invoices found for this client.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 