@extends('layouts.app')

@section('title', 'Create Customer')

@section('content')
    <div class="container">
        <h1>Create New Customer</h1>
        
        <form action="{{ route('customers.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control">
            </div>
            
            <button type="submit" class="btn btn-primary">Create Customer</button>
        </form>
    </div>
@endsection 