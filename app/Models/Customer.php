<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company_name',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'notes',
        'is_active'
    ];

    // Relationship with invoices
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    // Get total amount due from customer
    public function getTotalDueAttribute()
    {
        return $this->invoices()
            ->where('status', '!=', 'paid')
            ->sum('total_amount');
    }
}