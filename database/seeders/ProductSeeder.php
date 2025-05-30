<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::truncate(); // Optional: clears existing data

        Product::create([
            'company_id' => 2,
            'name' => 'Dog Shampoo',
            'description' => 'Gentle shampoo for all breeds',
            'price' => 12.99,
            'cost' => 5.00,
        ]);

        Product::create([
            'company_id' => 2,
            'name' => 'Cat Toy Mouse',
            'description' => 'Squeaky toy for cats',
            'price' => 3.49,
            'cost' => 1.25,
        ]);

        Product::create([
            'company_id' => 2,
            'name' => 'Bird Seed Mix',
            'description' => 'Premium wild bird seed blend',
            'price' => 8.25,
            'cost' => 3.00,
        ]);
    }
}
