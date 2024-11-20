<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'Web Development',
                'code' => 'WEB-001',
                'description' => 'Custom website development services',
                'unit_price' => 1500.00,
                'is_active' => true,
            ],
            [
                'name' => 'Mobile App Development',
                'code' => 'MOB-001',
                'description' => 'Mobile application development services',
                'unit_price' => 2500.00,
                'is_active' => true,
            ],
            [
                'name' => 'SEO Services',
                'code' => 'SEO-001',
                'description' => 'Search Engine Optimization services',
                'unit_price' => 750.00,
                'is_active' => true,
            ],
            [
                'name' => 'Consulting Hours',
                'code' => 'CONS-001',
                'description' => 'IT Consulting services per hour',
                'unit_price' => 150.00,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            $product['created_at'] = now();
            $product['updated_at'] = now();
            DB::table('products')->insert($product);
        }
    }
}
