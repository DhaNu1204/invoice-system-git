@extends('layouts.app')

@section('title', 'Payment Successful')

@section('content')
    <div class="container">
        <div class="success-message">
            <h1>Payment Successful!</h1>
            <p>Thank you for your payment of ${{ $payment->amount }}</p>
            <p>Transaction ID: {{ $payment->transaction_id }}</p>
            <a href="{{ route('invoices.show', $payment->invoice) }}" class="btn btn-primary">
                View Invoice
            </a>
        </div>
    </div>
@endsection 