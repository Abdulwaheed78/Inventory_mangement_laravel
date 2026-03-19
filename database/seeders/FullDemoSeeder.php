<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Log;
use App\Models\Order;
use App\Models\OrderTrans;
use App\Models\Payment;
use App\Models\PaymentMode;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseTrans;
use App\Models\Stage;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FullDemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->resetTables();

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'phone' => '9876543210',
            'password' => Hash::make('admin@12'),
            'role' => 1,
            'email_verified_at' => now(),
        ]);

        $categories = collect([
            ['name' => 'Electronics', 'description' => 'Devices, accessories, and office tech'],
            ['name' => 'Furniture', 'description' => 'Workstations, desks, and storage units'],
            ['name' => 'Stationery', 'description' => 'Daily office consumables and desk items'],
            ['name' => 'Appliances', 'description' => 'Utility equipment and office appliances'],
            ['name' => 'Packaging', 'description' => 'Boxes, wraps, labels, and logistics material'],
            ['name' => 'Cleaning', 'description' => 'Maintenance and hygiene supplies'],
            ['name' => 'Networking', 'description' => 'Routers, switches, and cabling parts'],
            ['name' => 'Safety', 'description' => 'Workplace protection and compliance gear'],
        ])->map(fn ($category) => Category::create($category));

        $warehouses = collect([
            ['name' => 'Mumbai Central Hub', 'location' => 'Mumbai'],
            ['name' => 'Delhi North Depot', 'location' => 'Delhi'],
            ['name' => 'Bengaluru Tech Store', 'location' => 'Bengaluru'],
            ['name' => 'Pune Reserve Warehouse', 'location' => 'Pune'],
            ['name' => 'Hyderabad Fulfillment Center', 'location' => 'Hyderabad'],
        ])->map(fn ($warehouse) => Warehouse::create($warehouse));

        $stages = collect(['New', 'Processing', 'Packed', 'Shipped', 'Delivered'])
            ->map(fn ($stage) => Stage::create(['name' => $stage]));

        $paymentModes = collect([
            ['name' => 'Cash', 'initial' => 'CSH', 'qr' => 'cash-manual'],
            ['name' => 'Bank Transfer', 'initial' => 'BNK', 'qr' => 'bank-transfer'],
            ['name' => 'UPI', 'initial' => 'UPI', 'qr' => 'upi://inventory-pro/demo'],
            ['name' => 'Card', 'initial' => 'CRD', 'qr' => 'card-terminal'],
        ])->map(fn ($mode) => PaymentMode::create($mode));

        $customers = collect(range(1, 35))->map(function ($index) {
            return Customer::create([
                'name' => "Customer {$index}",
                'email' => "customer{$index}@inventorypro.test",
                'phone' => '98' . str_pad((string) random_int(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'address' => fake()->streetAddress() . ', ' . fake()->city(),
            ]);
        });

        $suppliers = collect(range(1, 24))->map(function ($index) {
            return Supplier::create([
                'name' => "Supplier {$index}",
                'email' => "supplier{$index}@inventorypro.test",
                'phone' => '97' . str_pad((string) random_int(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'address' => fake()->streetAddress() . ', ' . fake()->city(),
            ]);
        });

        $products = collect(range(1, 80))->map(function ($index) use ($categories, $warehouses) {
            return Product::create([
                'name' => sprintf('Inventory Item %03d', $index),
                'sku' => 'SKU-' . str_pad((string) $index, 4, '0', STR_PAD_LEFT),
                'category_id' => $categories->random()->id,
                'price' => random_int(250, 2800),
                'stock_quantity' => 0,
                'warehouse_id' => $warehouses->random()->id,
                'status' => 'passive',
                'description' => fake()->sentence(10),
            ]);
        });

        foreach (range(1, 45) as $index) {
            $purchaseDate = Carbon::now()->subDays(random_int(15, 180))->startOfDay()->addHours(random_int(8, 17));
            $supplier = $suppliers->random();

            $purchase = Purchase::create([
                'supplier_id' => $supplier->id,
                'invoice_no' => 'PUR-' . now()->format('Y') . '-' . str_pad((string) $index, 4, '0', STR_PAD_LEFT),
                'total_amount' => 0,
                'purchase_date' => $purchaseDate->toDateString(),
                'status' => fake()->randomElement(['Pending', 'Received']),
                'created_at' => $purchaseDate,
                'updated_at' => $purchaseDate,
            ]);

            $totalAmount = 0;
            foreach ($products->random(random_int(2, 5))->unique('id') as $product) {
                $qty = random_int(15, 70);
                $buyPrice = random_int(120, 1800);
                $sellPrice = $buyPrice + random_int(40, 500);

                PurchaseTrans::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'qty' => $qty,
                    'price' => $buyPrice,
                    'created_at' => $purchaseDate,
                    'updated_at' => $purchaseDate,
                ]);

                $product->update([
                    'price' => $sellPrice,
                    'stock_quantity' => $product->stock_quantity + $qty,
                    'status' => 'active',
                    'updated_at' => $purchaseDate,
                ]);

                $totalAmount += $qty * $buyPrice;
            }

            $purchase->update([
                'total_amount' => $totalAmount,
                'status' => $totalAmount > 0 ? 'Received' : 'Pending',
                'updated_at' => $purchaseDate,
            ]);
        }

        foreach (range(1, 70) as $index) {
            $orderDate = Carbon::now()->subDays(random_int(0, 150))->startOfDay()->addHours(random_int(9, 20));
            $customer = $customers->random();
            $stage = $stages->random();

            $order = Order::create([
                'customer_id' => $customer->id,
                'total_amount' => 0,
                'order_date' => $orderDate->toDateString(),
                'stage_id' => $stage->id,
                'status' => 'Pending',
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ]);

            $totalAmount = 0;
            $availableProducts = Product::where('deleted', 'no')->where('stock_quantity', '>', 0)->get();
            $productCount = min($availableProducts->count(), random_int(1, 4));

            if ($productCount === 0) {
                $order->delete();
                continue;
            }

            foreach ($availableProducts->random($productCount) as $product) {
                $maxQty = min((int) $product->stock_quantity, 8);
                if ($maxQty < 1) {
                    continue;
                }

                $qty = random_int(1, $maxQty);
                OrderTrans::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'qty' => $qty,
                    'price' => $product->price,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]);

                $product->update([
                    'stock_quantity' => $product->stock_quantity - $qty,
                    'status' => ($product->stock_quantity - $qty) > 0 ? 'active' : 'passive',
                    'updated_at' => $orderDate,
                ]);

                $totalAmount += $qty * $product->price;
            }

            if ($totalAmount <= 0) {
                $order->delete();
                continue;
            }

            $order->update([
                'total_amount' => $totalAmount,
                'updated_at' => $orderDate,
            ]);

            $collectionType = fake()->randomElement(['full', 'partial', 'none']);
            $paidAmount = 0;

            if ($collectionType !== 'none') {
                $targetAmount = $collectionType === 'full'
                    ? $totalAmount
                    : max((int) floor($totalAmount * fake()->randomFloat(2, 0.25, 0.8)), 1);

                $remaining = $targetAmount;
                $installments = min(random_int(1, 3), $targetAmount);

                for ($installment = 1; $installment <= $installments; $installment++) {
                    if ($remaining <= 0) {
                        break;
                    }

                    $amount = $installment === $installments
                        ? $remaining
                        : random_int(1, max(1, $remaining - ($installments - $installment)));

                    $paymentDate = $orderDate->copy()->addDays(random_int(0, 10))->addHours(random_int(0, 8));
                    Payment::create([
                        'ordid' => $order->id,
                        'pmid' => $paymentModes->random()->id,
                        'amount' => $amount,
                        'date' => $paymentDate,
                        'created_at' => $paymentDate,
                        'updated_at' => $paymentDate,
                    ]);

                    $paidAmount += $amount;
                    $remaining -= $amount;
                }
            }

            $order->update([
                'status' => $paidAmount >= $totalAmount ? 'Received' : 'Pending',
                'updated_at' => $orderDate->copy()->addDays(1),
            ]);
        }

        Product::query()->each(function ($product) {
            $product->update([
                'status' => $product->stock_quantity > 0 ? 'active' : 'passive',
            ]);
        });

        $entityTables = [
            'categories' => Category::pluck('id')->all(),
            'warehouses' => Warehouse::pluck('id')->all(),
            'customers' => Customer::pluck('id')->all(),
            'suppliers' => Supplier::pluck('id')->all(),
            'products' => Product::pluck('id')->all(),
            'orders' => Order::pluck('id')->all(),
            'purchases' => Purchase::pluck('id')->all(),
            'payments' => Payment::pluck('id')->all(),
        ];

        foreach (range(1, 120) as $index) {
            $table = array_rand($entityTables);
            $ids = $entityTables[$table];
            $logDate = Carbon::now()->subDays(random_int(0, 90))->addMinutes(random_int(0, 1440));

            Log::create([
                'type' => fake()->randomElement(['insert', 'update', 'delete', 'login', 'logout']),
                'table' => $table,
                'by' => $admin->id,
                'rid' => $ids[array_rand($ids)],
                'created_at' => $logDate,
                'updated_at' => $logDate,
            ]);
        }
    }

    private function resetTables(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }

        foreach ([
            'logs',
            'payments',
            'order_trans',
            'orders',
            'purchase_trans',
            'purchases',
            'products',
            'customers',
            'suppliers',
            'payment_modes',
            'stages',
            'warehouses',
            'categories',
            'users',
        ] as $table) {
            DB::table($table)->truncate();
        }

        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }
}
