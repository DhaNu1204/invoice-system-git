<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('settings')->insert([
            'company_name' => 'Your Company Name',
            'company_email' => 'info@yourcompany.com',
            'company_phone' => '+1 234 567 890',
            'company_address' => '789 Business Avenue, Suite 100',
            'tax_number' => 'TAX123456789',
            'currency' => 'USD',
            'default_tax_rate' => 10.00,
            'invoice_prefix' => 'INV-',
            'invoice_starting_number' => 1000,
            'invoice_footer_text' => 'Thank you for your business!',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
