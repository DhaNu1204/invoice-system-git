<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'company_email',
        'company_phone',
        'company_address',
        'tax_number',
        'currency',
        'default_tax_rate',
        'invoice_prefix',
        'invoice_starting_number',
        'invoice_footer_text',
        'logo_path'
    ];

    // Get settings helper method
    public static function getSetting($key)
    {
        $setting = self::first();
        return $setting ? $setting->$key : null;
    }
} 