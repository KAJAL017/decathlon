<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('warehouses')->truncate();

        $now = now();
        $base = [
            'address_line1' => null, 'address_line2' => null,
            'city' => null, 'state' => null, 'pincode' => null,
            'latitude' => null, 'longitude' => null,
            'contact_phone' => null, 'notes' => null,
        ];

        $warehouses = [
            array_merge($base, [
                'name' => 'Mumbai Central Warehouse', 'code' => 'MUM-01', 'type' => 'main',
                'contact_name' => 'Rajesh Kumar', 'contact_email' => 'mumbai@decathlon.com',
                'contact_phone' => '+91 98765 43210',
                'address_line1' => 'Plot 45, MIDC Industrial Area', 'address_line2' => 'Andheri East',
                'city' => 'Mumbai', 'state' => 'Maharashtra', 'country' => 'India', 'pincode' => '400093',
                'latitude' => 19.1136, 'longitude' => 72.8697,
                'is_active' => true, 'is_default' => true, 'accepts_returns' => true,
                'processing_days' => 1, 'notes' => 'Main fulfillment center for West India',
                'created_at' => $now, 'updated_at' => $now,
            ]),
            array_merge($base, [
                'name' => 'Delhi NCR Warehouse', 'code' => 'DEL-01', 'type' => 'regional',
                'contact_name' => 'Priya Sharma', 'contact_email' => 'delhi@decathlon.com',
                'contact_phone' => '+91 98765 11111',
                'address_line1' => 'Sector 63, Noida', 'address_line2' => 'Near Electronic City',
                'city' => 'Noida', 'state' => 'Uttar Pradesh', 'country' => 'India', 'pincode' => '201301',
                'latitude' => 28.6139, 'longitude' => 77.2090,
                'is_active' => true, 'is_default' => false, 'accepts_returns' => true,
                'processing_days' => 1, 'notes' => 'Serves Delhi NCR and North India',
                'created_at' => $now, 'updated_at' => $now,
            ]),
            array_merge($base, [
                'name' => 'Bangalore South Hub', 'code' => 'BLR-01', 'type' => 'regional',
                'contact_name' => 'Suresh Nair', 'contact_email' => 'bangalore@decathlon.com',
                'contact_phone' => '+91 98765 22222',
                'address_line1' => 'Bommasandra Industrial Area', 'address_line2' => 'Phase 4',
                'city' => 'Bangalore', 'state' => 'Karnataka', 'country' => 'India', 'pincode' => '560099',
                'latitude' => 12.9716, 'longitude' => 77.5946,
                'is_active' => true, 'is_default' => false, 'accepts_returns' => true,
                'processing_days' => 2, 'notes' => 'South India fulfillment center',
                'created_at' => $now, 'updated_at' => $now,
            ]),
            array_merge($base, [
                'name' => 'Chennai Port Warehouse', 'code' => 'CHN-01', 'type' => 'regional',
                'contact_name' => 'Anand Rajan', 'contact_email' => 'chennai@decathlon.com',
                'contact_phone' => '+91 98765 33333',
                'address_line1' => 'Manali Industrial Estate',
                'city' => 'Chennai', 'state' => 'Tamil Nadu', 'country' => 'India', 'pincode' => '600068',
                'latitude' => 13.0827, 'longitude' => 80.2707,
                'is_active' => true, 'is_default' => false, 'accepts_returns' => false,
                'processing_days' => 2, 'notes' => 'Import/export handling warehouse',
                'created_at' => $now, 'updated_at' => $now,
            ]),
            array_merge($base, [
                'name' => 'Kolkata East Depot', 'code' => 'KOL-01', 'type' => 'regional',
                'contact_name' => 'Debashish Roy', 'contact_email' => 'kolkata@decathlon.com',
                'contact_phone' => '+91 98765 44444',
                'address_line1' => 'Dankuni Industrial Complex',
                'city' => 'Kolkata', 'state' => 'West Bengal', 'country' => 'India', 'pincode' => '712311',
                'is_active' => true, 'is_default' => false, 'accepts_returns' => true,
                'processing_days' => 2, 'notes' => 'East India and North-East distribution',
                'created_at' => $now, 'updated_at' => $now,
            ]),
            array_merge($base, [
                'name' => 'Hyderabad Tech Park Store', 'code' => 'HYD-01', 'type' => 'regional',
                'contact_name' => 'Venkat Reddy', 'contact_email' => 'hyderabad@decathlon.com',
                'contact_phone' => '+91 98765 55555',
                'address_line1' => 'HITEC City, Madhapur',
                'city' => 'Hyderabad', 'state' => 'Telangana', 'country' => 'India', 'pincode' => '500081',
                'is_active' => true, 'is_default' => false, 'accepts_returns' => true,
                'processing_days' => 1, 'notes' => 'Serves Hyderabad metro area',
                'created_at' => $now, 'updated_at' => $now,
            ]),
            array_merge($base, [
                'name' => 'Virtual Dropship Hub', 'code' => 'VRT-01', 'type' => 'virtual',
                'contact_name' => 'System Admin', 'contact_email' => 'dropship@decathlon.com',
                'country' => 'India',
                'is_active' => true, 'is_default' => false, 'accepts_returns' => false,
                'processing_days' => 3, 'notes' => 'Virtual warehouse for dropship orders - no physical location',
                'created_at' => $now, 'updated_at' => $now,
            ]),
            array_merge($base, [
                'name' => 'Pune Supplier Dropship', 'code' => 'PUN-DS', 'type' => 'dropship',
                'contact_name' => 'Amit Joshi', 'contact_email' => 'pune.ds@decathlon.com',
                'contact_phone' => '+91 98765 66666',
                'address_line1' => 'Pimpri-Chinchwad Industrial Area',
                'city' => 'Pune', 'state' => 'Maharashtra', 'country' => 'India', 'pincode' => '411018',
                'is_active' => false, 'is_default' => false, 'accepts_returns' => false,
                'processing_days' => 5, 'notes' => 'Inactive - supplier contract under review',
                'created_at' => $now, 'updated_at' => $now,
            ]),
        ];

        DB::table('warehouses')->insert($warehouses);
        $this->command->info('Warehouses seeded: ' . count($warehouses) . ' records');
    }
}
