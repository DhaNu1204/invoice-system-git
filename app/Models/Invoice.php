<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'invoice_number',
        'issue_date',
        'due_date',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'total_amount',
        'notes',
        'status'
    ];

    protected $dates = [
        'issue_date',
        'due_date'
    ];

    // Relationship with customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relationship with invoice items
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    // Relationship with payments
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Get paid amount
    public function getPaidAmountAttribute()
    {
        return $this->payments->sum('amount');
    }

    // Get due amount
    public function getDueAmountAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }

    public function sendInvoice()
    {
        Mail::to($this->customer->email)
            ->send(new InvoiceMail($this));
        
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    public function updatePaymentStatus()
    {
        if ($this->due_amount <= 0) {
            $this->update(['status' => 'paid']);
        } elseif ($this->paid_amount > 0) {
            $this->update(['status' => 'partial']);
        }
    }
}