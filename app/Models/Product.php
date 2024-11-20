<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'unit_price',
        'is_active'
    ];

    // Relationship with invoice items
    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}