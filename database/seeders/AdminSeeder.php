<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Warehouse;
use App\Models\Customer;
use App\Models\Stage;
use App\Models\Supplier;
use App\Models\Product;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin@12'),
        ]);

        // Seed multiple tables with 5 records each
        for ($i = 0; $i < 5; $i++) {
            Category::create([
                'name' => 'Category ' . ($i + 1),
                'description' => 'Description for category ' . ($i + 1),
            ]);

            Warehouse::create([
                'name' => 'Warehouse ' . ($i + 1),
                'location' => 'Location ' . ($i + 1),
            ]);

            Customer::create([
                'name' => 'Customer ' . ($i + 1),
                'email' => 'customer' . ($i + 1) . '@example.com',
                'phone' => '123456789' . $i,
            ]);

            Stage::create([
                'name' => 'Stage ' . ($i + 1)
            ]);

            Supplier::create([
                'name' => 'Supplier ' . ($i + 1),
                'email' => 'supplier' . ($i + 1) . '@example.com',
                'phone' => '987654321' . $i,
            ]);

            Product::create([
                'name' => 'Product ' . ($i + 1), // Correct concatenation
                'sku' => 'sku' . ($i + 1), // Correct concatenation
                'category_id' => $i + 1, // Remove square brackets
                'price' => 500,
                'stock_quantity' => 20,
                'warehouse_id' => $i + 1, // Remove square brackets
                'description' => 'Description of the product ' . ($i + 1), // Correct concatenation
                'status' => 'active'
            ]);
        }
    }
}
