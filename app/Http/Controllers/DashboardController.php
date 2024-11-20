<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_customers' => Customer::count(),
            'total_invoices' => Invoice::count(),
            'total_paid' => Payment::where('status', 'completed')->sum('amount'),
            'recent_invoices' => Invoice::with('customer')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'pending_payments' => Invoice::where('status', '!=', 'paid')
                ->with('customer')
                ->orderBy('due_date', 'asc')
                ->limit(5)
                ->get(),
        ];

        return view('dashboard', $data);
    }
}
