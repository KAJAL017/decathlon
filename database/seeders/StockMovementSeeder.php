<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockMovement;
use App\Models\Product;
use Carbon\Carbon;

class StockMovementSeeder extends Seeder
{
    public function run(): void
    {
        StockMovement::truncate();

        // Get available product IDs (use first 5 or whatever exists)
        $productIds = Product::orderBy('id')->limit(5)->pluck('id')->toArray();

        if (empty($productIds)) {
            $this->command->warn('StockMovementSeeder: No products found. Skipping.');
            return;
        }

        $count = count($productIds);
        // Helper to get a product ID by index (wraps around)
        $pid = fn(int $i) => $productIds[$i % $count];

        $movements = [
            // 1 - Initial stock purchase
            [
                'product_id'      => $pid(0),
                'type'            => 'purchase',
                'quantity'        => 100,
                'quantity_before' => 0,
                'quantity_after'  => 100,
                'reference_type'  => 'purchase_order',
                'reference_id'    => 1001,
                'notes'           => 'Initial stock purchase from supplier',
                'cost_per_unit'   => 25.00,
                'location'        => 'Main Warehouse',
                'created_at'      => Carbon::now()->subDays(60),
                'updated_at'      => Carbon::now()->subDays(60),
            ],
            // 2
            [
                'product_id'      => $pid(1),
                'type'            => 'purchase',
                'quantity'        => 150,
                'quantity_before' => 0,
                'quantity_after'  => 150,
                'reference_type'  => 'purchase_order',
                'reference_id'    => 1002,
                'notes'           => 'Initial stock purchase',
                'cost_per_unit'   => 18.50,
                'location'        => 'Main Warehouse',
                'created_at'      => Carbon::now()->subDays(58),
                'updated_at'      => Carbon::now()->subDays(58),
            ],
            // 3
            [
                'product_id'      => $pid(2),
                'type'            => 'purchase',
                'quantity'        => 75,
                'quantity_before' => 0,
                'quantity_after'  => 75,
                'reference_type'  => 'purchase_order',
                'reference_id'    => 1003,
                'notes'           => 'Bulk purchase Q1',
                'cost_per_unit'   => 42.00,
                'location'        => 'Main Warehouse',
                'created_at'      => Carbon::now()->subDays(55),
                'updated_at'      => Carbon::now()->subDays(55),
            ],
            // 4 - Sale
            [
                'product_id'      => $pid(0),
                'type'            => 'sale',
                'quantity'        => -15,
                'quantity_before' => 100,
                'quantity_after'  => 85,
                'reference_type'  => 'order',
                'reference_id'    => 5001,
                'notes'           => 'Online order #5001',
                'location'        => 'Main Warehouse',
                'created_at'      => Carbon::now()->subDays(50),
                'updated_at'      => Carbon::now()->subDays(50),
            ],
            // 5
            [
                'product_id'      => $pid(1),
                'type'            => 'sale',
                'quantity'        => -30,
                'quantity_before' => 150,
                'quantity_after'  => 120,
                'reference_type'  => 'order',
                'reference_id'    => 5002,
                'notes'           => 'Bulk order #5002',
                'location'        => 'Main Warehouse',
                'created_at'      => Carbon::now()->subDays(45),
                'updated_at'      => Carbon::now()->subDays(45),
            ],
            // 6
            [
                'product_id'      => $pid(2),
                'type'            => 'sale',
                'quantity'        => -10,
                'quantity_before' => 75,
                'quantity_after'  => 65,
                'reference_type'  => 'order',
                'reference_id'    => 5003,
                'notes'           => 'Online order #5003',
                'location'        => 'Main Warehouse',
                'created_at'      => Carbon::now()->subDays(40),
                'updated_at'      => Carbon::now()->subDays(40),
            ],
            // 7 - Return
            [
                'product_id'      => $pid(0),
                'type'            => 'return',
                'quantity'        => 3,
                'quantity_before' => 85,
                'quantity_after'  => 88,
                'reference_type'  => 'order',
                'reference_id'    => 5001,
                'notes'           => 'Customer return — wrong size',
                'location'        => 'Main Warehouse',
                'created_at'      => Carbon::now()->subDays(38),
                'updated_at'      => Carbon::now()->subDays(38),
            ],
            // 8 - Adjustment
            [
                'product_id'      => $pid(3),
                'type'            => 'adjustment',
                'quantity'        => -5,
                'quantity_before' => 50,
                'quantity_after'  => 45,
                'notes'           => 'Stock count correction after audit',
                'location'        => 'Main Warehouse',
                'created_at'      => Carbon::now()->subDays(35),
                'updated_at'      => Carbon::now()->subDays(35),
            ],
            // 9
            [
                'product_id'      => $pid(4),
                'type'            => 'adjustment',
                'quantity'        => 10,
                'quantity_before' => 20,
                'quantity_after'  => 30,
                'notes'           => 'Found misplaced stock during warehouse reorganization',
                'location'        => 'Warehouse B',
                'created_at'      => Carbon::now()->subDays(30),
                'updated_at'      => Carbon::now()->subDays(30),
            ],
            // 10 - Damage
            [
                'product_id'      => $pid(1),
                'type'            => 'damage',
                'quantity'        => -4,
                'quantity_before' => 120,
                'quantity_after'  => 116,
                'notes'           => 'Damaged during transit — water damage',
                'location'        => 'Main Warehouse',
                'created_at'      => Carbon::now()->subDays(28),
                'updated_at'      => Carbon::now()->subDays(28),
            ],
            // 11 - Transfer
            [
                'product_id'      => $pid(0),
                'type'            => 'transfer',
                'quantity'        => -20,
                'quantity_before' => 88,
                'quantity_after'  => 68,
                'notes'           => 'Transfer to Store B for summer display',
                'location'        => 'Store B',
                'created_at'      => Carbon::now()->subDays(25),
                'updated_at'      => Carbon::now()->subDays(25),
            ],
            // 12 - Purchase restock
            [
                'product_id'      => $pid(2),
                'type'            => 'purchase',
                'quantity'        => 50,
                'quantity_before' => 65,
                'quantity_after'  => 115,
                'reference_type'  => 'purchase_order',
                'reference_id'    => 1010,
                'notes'           => 'Restock order Q2',
                'cost_per_unit'   => 40.00,
                'location'        => 'Main Warehouse',
                'created_at'      => Carbon::now()->subDays(20),
                'updated_at'      => Carbon::now()->subDays(20),
            ],
            // 13 - Sale batch
            [
                'product_id'      => $pid(0),
                'type'            => 'sale',
                'quantity'        => -25,
                'quantity_before' => 68,
                'quantity_after'  => 43,
                'reference_type'  => 'order',
                'reference_id'    => 5020,
                'notes'           => 'Summer sale orders batch',
                'location'        => 'Main Warehouse',
                'created_at'      => Carbon::now()->subDays(15),
                'updated_at'      => Carbon::now()->subDays(15),
            ],
            // 14
            [
                'product_id'      => $pid(1),
                'type'            => 'sale',
                'quantity'        => -20,
                'quantity_before' => 116,
                'quantity_after'  => 96,
                'reference_type'  => 'order',
                'reference_id'    => 5021,
                'notes'           => 'Weekend sale orders',
                'location'        => 'Main Warehouse',
                'created_at'      => Carbon::now()->subDays(10),
                'updated_at'      => Carbon::now()->subDays(10),
            ],
            // 15 - Expired
            [
                'product_id'      => $pid(3),
                'type'            => 'expired',
                'quantity'        => -2,
                'quantity_before' => 45,
                'quantity_after'  => 43,
                'notes'           => 'Expired items removed from inventory',
                'location'        => 'Main Warehouse',
                'created_at'      => Carbon::now()->subDays(8),
                'updated_at'      => Carbon::now()->subDays(8),
            ],
            // 16 - Adjustment
            [
                'product_id'      => $pid(2),
                'type'            => 'adjustment',
                'quantity'        => -3,
                'quantity_before' => 115,
                'quantity_after'  => 112,
                'notes'           => 'Quarterly stock audit correction',
                'location'        => 'Main Warehouse',
                'created_at'      => Carbon::now()->subDays(5),
                'updated_at'      => Carbon::now()->subDays(5),
            ],
            // 17 - Emergency restock
            [
                'product_id'      => $pid(4),
                'type'            => 'purchase',
                'quantity'        => 80,
                'quantity_before' => 30,
                'quantity_after'  => 110,
                'reference_type'  => 'purchase_order',
                'reference_id'    => 1020,
                'notes'           => 'Emergency restock — low stock alert triggered',
                'cost_per_unit'   => 15.00,
                'location'        => 'Main Warehouse',
                'created_at'      => Carbon::now()->subDays(3),
                'updated_at'      => Carbon::now()->subDays(3),
            ],
            // 18 - Return
            [
                'product_id'      => $pid(1),
                'type'            => 'return',
                'quantity'        => 5,
                'quantity_before' => 96,
                'quantity_after'  => 101,
                'reference_type'  => 'order',
                'reference_id'    => 5021,
                'notes'           => 'Bulk return — customer changed mind',
                'location'        => 'Main Warehouse',
                'created_at'      => Carbon::now()->subDays(2),
                'updated_at'      => Carbon::now()->subDays(2),
            ],
            // 19 - Today sale
            [
                'product_id'      => $pid(0),
                'type'            => 'sale',
                'quantity'        => -8,
                'quantity_before' => 43,
                'quantity_after'  => 35,
                'reference_type'  => 'order',
                'reference_id'    => 5030,
                'notes'           => "Today's online orders",
                'location'        => 'Main Warehouse',
                'created_at'      => Carbon::now()->subHours(5),
                'updated_at'      => Carbon::now()->subHours(5),
            ],
            // 20 - Today purchase
            [
                'product_id'      => $pid(2),
                'type'            => 'purchase',
                'quantity'        => 25,
                'quantity_before' => 112,
                'quantity_after'  => 137,
                'reference_type'  => 'purchase_order',
                'reference_id'    => 1025,
                'notes'           => 'Top-up order received today',
                'cost_per_unit'   => 38.00,
                'location'        => 'Main Warehouse',
                'created_at'      => Carbon::now()->subHours(2),
                'updated_at'      => Carbon::now()->subHours(2),
            ],
        ];

        foreach ($movements as $data) {
            StockMovement::create($data);
        }

        $this->command->info('StockMovementSeeder: 20 stock movements seeded successfully.');
    }
}
