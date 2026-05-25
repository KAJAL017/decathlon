<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding orders...');

        // Get some real products from DB
        $products = Product::where('status', 'active')->limit(20)->get(['id', 'name', 'sku_prefix']);

        if ($products->isEmpty()) {
            $this->command->warn('No active products found. Using dummy product data.');
        }

        $customers = [
            ['name' => 'Rahul Sharma',    'email' => 'rahul.sharma@gmail.com',   'phone' => '9876543210'],
            ['name' => 'Priya Patel',     'email' => 'priya.patel@gmail.com',    'phone' => '9812345678'],
            ['name' => 'Amit Kumar',      'email' => 'amit.kumar@yahoo.com',     'phone' => '9988776655'],
            ['name' => 'Sneha Reddy',     'email' => 'sneha.reddy@gmail.com',    'phone' => '9765432109'],
            ['name' => 'Vikram Singh',    'email' => 'vikram.singh@outlook.com', 'phone' => '9654321098'],
            ['name' => 'Anjali Mehta',    'email' => 'anjali.mehta@gmail.com',   'phone' => '9543210987'],
            ['name' => 'Rohan Gupta',     'email' => 'rohan.gupta@gmail.com',    'phone' => '9432109876'],
            ['name' => 'Kavya Nair',      'email' => 'kavya.nair@gmail.com',     'phone' => '9321098765'],
            ['name' => 'Arjun Verma',     'email' => 'arjun.verma@gmail.com',    'phone' => '9210987654'],
            ['name' => 'Deepika Joshi',   'email' => 'deepika.joshi@gmail.com',  'phone' => '9109876543'],
        ];

        $addresses = [
            ['address' => '12, MG Road, Koramangala', 'city' => 'Bengaluru', 'state' => 'Karnataka', 'pincode' => '560034'],
            ['address' => '45, Linking Road, Bandra West', 'city' => 'Mumbai', 'state' => 'Maharashtra', 'pincode' => '400050'],
            ['address' => '78, Connaught Place', 'city' => 'New Delhi', 'state' => 'Delhi', 'pincode' => '110001'],
            ['address' => '23, Anna Salai, Teynampet', 'city' => 'Chennai', 'state' => 'Tamil Nadu', 'pincode' => '600018'],
            ['address' => '56, Park Street', 'city' => 'Kolkata', 'state' => 'West Bengal', 'pincode' => '700016'],
            ['address' => '34, Jubilee Hills', 'city' => 'Hyderabad', 'state' => 'Telangana', 'pincode' => '500033'],
            ['address' => '89, CG Road, Navrangpura', 'city' => 'Ahmedabad', 'state' => 'Gujarat', 'pincode' => '380009'],
            ['address' => '15, FC Road, Shivajinagar', 'city' => 'Pune', 'state' => 'Maharashtra', 'pincode' => '411005'],
        ];

        $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
        $paymentMethods = ['cod', 'razorpay', 'upi', 'card'];
        $paymentStatuses = ['pending', 'paid', 'paid', 'paid']; // mostly paid

        $dummyProducts = [
            ['name' => 'Decathlon Running Shoes T500', 'sku' => 'RUN-T500', 'price' => 1999.00],
            ['name' => 'Kipsta Football Size 5',       'sku' => 'FOOT-S5',  'price' => 799.00],
            ['name' => 'Domyos Yoga Mat 8mm',          'sku' => 'YOGA-8MM', 'price' => 1299.00],
            ['name' => 'B\'Twin Cycling Helmet',       'sku' => 'HELM-BT',  'price' => 2499.00],
            ['name' => 'Quechua Hiking Backpack 20L',  'sku' => 'BACK-20L', 'price' => 1799.00],
            ['name' => 'Nabaiji Swimming Goggles',     'sku' => 'SWIM-GOG', 'price' => 599.00],
            ['name' => 'Artengo Tennis Racket',        'sku' => 'TEN-RAC',  'price' => 3499.00],
            ['name' => 'Kalenji Running T-Shirt',      'sku' => 'RUN-TSH',  'price' => 699.00],
        ];

        $created = 0;

        for ($i = 0; $i < 15; $i++) {
            $customer = $customers[array_rand($customers)];
            $addr     = $addresses[array_rand($addresses)];
            $status   = $statuses[array_rand($statuses)];
            $payMethod= $paymentMethods[array_rand($paymentMethods)];
            $payStatus= $status === 'delivered' ? 'paid' : ($payMethod === 'cod' ? 'pending' : 'paid');

            // Pick 1-3 items
            $itemCount = rand(1, 3);
            $orderItems = [];
            $subtotal   = 0;

            for ($j = 0; $j < $itemCount; $j++) {
                if ($products->isNotEmpty()) {
                    $p   = $products->random();
                    $qty = rand(1, 3);
                    // Get price from variant or use default
                    $variant = \App\Models\ProductVariant::where('product_id', $p->id)->first();
                    $price   = $variant ? (float)($variant->sale_price ?: $variant->price ?: 999) : rand(499, 4999);
                    $orderItems[] = [
                        'product_id'   => $p->id,
                        'product_name' => $p->name,
                        'product_sku'  => $p->sku_prefix,
                        'quantity'     => $qty,
                        'unit_price'   => $price,
                        'total_price'  => $price * $qty,
                    ];
                    $subtotal += $price * $qty;
                } else {
                    $dp  = $dummyProducts[array_rand($dummyProducts)];
                    $qty = rand(1, 3);
                    $orderItems[] = [
                        'product_id'   => null,
                        'product_name' => $dp['name'],
                        'product_sku'  => $dp['sku'],
                        'quantity'     => $qty,
                        'unit_price'   => $dp['price'],
                        'total_price'  => $dp['price'] * $qty,
                    ];
                    $subtotal += $dp['price'] * $qty;
                }
            }

            $shippingAmount = $subtotal >= 999 ? 0 : 49;
            $taxAmount      = round($subtotal * 0.18, 2);
            $total          = $subtotal + $shippingAmount + $taxAmount;

            DB::beginTransaction();
            try {
                $order = Order::create([
                    'order_number'     => Order::generateOrderNumber(),
                    'status'           => $status,
                    'payment_status'   => $payStatus,
                    'payment_method'   => $payMethod,
                    'payment_reference'=> $payMethod !== 'cod' ? 'PAY' . strtoupper(substr(md5(rand()), 0, 10)) : null,
                    'customer_name'    => $customer['name'],
                    'customer_email'   => $customer['email'],
                    'customer_phone'   => $customer['phone'],
                    'shipping_name'    => $customer['name'],
                    'shipping_address' => $addr['address'],
                    'shipping_city'    => $addr['city'],
                    'shipping_state'   => $addr['state'],
                    'shipping_pincode' => $addr['pincode'],
                    'shipping_country' => 'India',
                    'billing_name'     => $customer['name'],
                    'billing_address'  => $addr['address'],
                    'billing_city'     => $addr['city'],
                    'billing_state'    => $addr['state'],
                    'billing_pincode'  => $addr['pincode'],
                    'subtotal'         => $subtotal,
                    'discount_amount'  => 0,
                    'shipping_amount'  => $shippingAmount,
                    'tax_amount'       => $taxAmount,
                    'total_amount'     => $total,
                    'shipping_method'  => 'standard',
                    'tracking_number'  => in_array($status, ['shipped', 'delivered']) ? 'SR' . rand(100000000, 999999999) : null,
                    'shipping_carrier' => in_array($status, ['shipped', 'delivered']) ? 'Delhivery' : null,
                    'shipped_at'       => in_array($status, ['shipped', 'delivered']) ? now()->subDays(rand(1, 5)) : null,
                    'delivered_at'     => $status === 'delivered' ? now()->subDays(rand(0, 2)) : null,
                    'source'           => 'manual',
                    'created_by'       => 1,
                ]);

                foreach ($orderItems as $item) {
                    OrderItem::create(array_merge($item, ['order_id' => $order->id]));
                }

                DB::commit();
                $created++;
                $this->command->info("  ✓ {$order->order_number} — {$customer['name']} — ₹{$total} — {$status}");
            } catch (\Exception $e) {
                DB::rollBack();
                $this->command->error("  ✗ Failed: " . $e->getMessage());
            }
        }

        $this->command->info("\nDone: {$created} orders created.");
        $this->command->info('Go to Admin → Orders to view them.');
    }
}
