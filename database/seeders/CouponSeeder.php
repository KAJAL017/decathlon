<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $coupons = [
            // ── Percentage Coupons ──────────────────────────────────────
            ['code'=>'WELCOME20',  'name'=>'Welcome 20% OFF',         'discount_type'=>'percentage',   'discount_value'=>20, 'max_discount_amount'=>500,  'min_order_amount'=>499,  'usage_limit'=>null, 'usage_per_user'=>1, 'used_count'=>342, 'applies_to'=>'all',                  'customer_eligibility'=>'new_customers', 'starts_at'=>null,                    'expires_at'=>null,                          'combine_with_other_coupons'=>false,'combine_with_promotions'=>true, 'is_active'=>true],
            ['code'=>'SPORT15',    'name'=>'Sports 15% OFF',          'discount_type'=>'percentage',   'discount_value'=>15, 'max_discount_amount'=>750,  'min_order_amount'=>999,  'usage_limit'=>500,  'usage_per_user'=>2, 'used_count'=>189, 'applies_to'=>'all',                  'customer_eligibility'=>'all',           'starts_at'=>null,                    'expires_at'=>$now->copy()->addDays(30), 'combine_with_other_coupons'=>false,'combine_with_promotions'=>true, 'is_active'=>true],
            ['code'=>'DECATHLON10','name'=>'Decathlon 10% OFF',       'discount_type'=>'percentage',   'discount_value'=>10, 'max_discount_amount'=>300,  'min_order_amount'=>299,  'usage_limit'=>null, 'usage_per_user'=>5, 'used_count'=>1203,'applies_to'=>'all',                  'customer_eligibility'=>'all',           'starts_at'=>null,                    'expires_at'=>null,                          'combine_with_other_coupons'=>false,'combine_with_promotions'=>true, 'is_active'=>true],
            ['code'=>'SUMMER25',   'name'=>'Summer 25% OFF',          'discount_type'=>'percentage',   'discount_value'=>25, 'max_discount_amount'=>1000, 'min_order_amount'=>799,  'usage_limit'=>300,  'usage_per_user'=>1, 'used_count'=>0,   'applies_to'=>'all',                  'customer_eligibility'=>'all',           'starts_at'=>$now->copy()->addDays(5),'expires_at'=>$now->copy()->addDays(35), 'combine_with_other_coupons'=>false,'combine_with_promotions'=>false,'is_active'=>true],
            ['code'=>'FITNESS30',  'name'=>'Fitness 30% OFF',         'discount_type'=>'percentage',   'discount_value'=>30, 'max_discount_amount'=>1200, 'min_order_amount'=>1499, 'usage_limit'=>200,  'usage_per_user'=>1, 'used_count'=>67,  'applies_to'=>'specific_categories',  'customer_eligibility'=>'all',           'starts_at'=>null,                    'expires_at'=>$now->copy()->addDays(15), 'combine_with_other_coupons'=>false,'combine_with_promotions'=>true, 'is_active'=>true],

            // ── Fixed Amount Coupons ────────────────────────────────────
            ['code'=>'SAVE200',    'name'=>'Save ₹200',               'discount_type'=>'fixed_amount', 'discount_value'=>200,'max_discount_amount'=>null, 'min_order_amount'=>999,  'usage_limit'=>null, 'usage_per_user'=>3, 'used_count'=>876, 'applies_to'=>'all',                  'customer_eligibility'=>'all',           'starts_at'=>null,                    'expires_at'=>null,                          'combine_with_other_coupons'=>false,'combine_with_promotions'=>true, 'is_active'=>true],
            ['code'=>'SAVE500',    'name'=>'Save ₹500',               'discount_type'=>'fixed_amount', 'discount_value'=>500,'max_discount_amount'=>null, 'min_order_amount'=>2499, 'usage_limit'=>null, 'usage_per_user'=>2, 'used_count'=>432, 'applies_to'=>'all',                  'customer_eligibility'=>'all',           'starts_at'=>null,                    'expires_at'=>null,                          'combine_with_other_coupons'=>false,'combine_with_promotions'=>true, 'is_active'=>true],
            ['code'=>'FLAT100',    'name'=>'Flat ₹100 OFF',           'discount_type'=>'fixed_amount', 'discount_value'=>100,'max_discount_amount'=>null, 'min_order_amount'=>499,  'usage_limit'=>1000, 'usage_per_user'=>5, 'used_count'=>567, 'applies_to'=>'all',                  'customer_eligibility'=>'all',           'starts_at'=>null,                    'expires_at'=>$now->copy()->addDays(60), 'combine_with_other_coupons'=>true, 'combine_with_promotions'=>true, 'is_active'=>true],

            // ── Free Shipping Coupons ───────────────────────────────────
            ['code'=>'FREESHIP',   'name'=>'Free Shipping',           'discount_type'=>'free_shipping','discount_value'=>0,  'max_discount_amount'=>null, 'min_order_amount'=>299,  'usage_limit'=>null, 'usage_per_user'=>null,'used_count'=>3421,'applies_to'=>'all',                 'customer_eligibility'=>'all',           'starts_at'=>null,                    'expires_at'=>null,                          'combine_with_other_coupons'=>true, 'combine_with_promotions'=>true, 'is_active'=>true],
            ['code'=>'SHIPFREE99', 'name'=>'Free Ship on ₹99+',       'discount_type'=>'free_shipping','discount_value'=>0,  'max_discount_amount'=>null, 'min_order_amount'=>99,   'usage_limit'=>100,  'usage_per_user'=>1, 'used_count'=>45,  'applies_to'=>'all',                  'customer_eligibility'=>'new_customers', 'starts_at'=>null,                    'expires_at'=>$now->copy()->addDays(7),  'combine_with_other_coupons'=>false,'combine_with_promotions'=>true, 'is_active'=>true],

            // ── Buy X Get Y Coupons ─────────────────────────────────────
            ['code'=>'B2G1FREE',   'name'=>'Buy 2 Get 1 Free',        'discount_type'=>'buy_x_get_y',  'discount_value'=>0,  'max_discount_amount'=>null, 'min_order_amount'=>null, 'usage_limit'=>500,  'usage_per_user'=>2, 'used_count'=>234,'applies_to'=>'all',                   'customer_eligibility'=>'all',           'starts_at'=>null,                    'expires_at'=>$now->copy()->addDays(20), 'combine_with_other_coupons'=>false,'combine_with_promotions'=>false,'is_active'=>true,  'buy_quantity'=>2,'get_quantity'=>1,'get_discount_percent'=>100],
            ['code'=>'B3G1HALF',   'name'=>'Buy 3 Get 1 at 50%',      'discount_type'=>'buy_x_get_y',  'discount_value'=>0,  'max_discount_amount'=>null, 'min_order_amount'=>null, 'usage_limit'=>200,  'usage_per_user'=>1, 'used_count'=>89, 'applies_to'=>'specific_categories',  'customer_eligibility'=>'all',           'starts_at'=>null,                    'expires_at'=>$now->copy()->addDays(14), 'combine_with_other_coupons'=>false,'combine_with_promotions'=>false,'is_active'=>true,  'buy_quantity'=>3,'get_quantity'=>1,'get_discount_percent'=>50],

            // ── Expired Coupons ─────────────────────────────────────────
            ['code'=>'DIWALI40',   'name'=>'Diwali 40% OFF (Expired)','discount_type'=>'percentage',   'discount_value'=>40, 'max_discount_amount'=>1500, 'min_order_amount'=>999,  'usage_limit'=>2000, 'usage_per_user'=>2, 'used_count'=>1987,'applies_to'=>'all',                  'customer_eligibility'=>'all',           'starts_at'=>$now->copy()->subDays(40),'expires_at'=>$now->copy()->subDays(30), 'combine_with_other_coupons'=>false,'combine_with_promotions'=>false,'is_active'=>true],
            ['code'=>'NEWYEAR50',  'name'=>'New Year 50% OFF (Expired)','discount_type'=>'percentage', 'discount_value'=>50, 'max_discount_amount'=>2000, 'min_order_amount'=>1499, 'usage_limit'=>1000, 'usage_per_user'=>1, 'used_count'=>987, 'applies_to'=>'all',                  'customer_eligibility'=>'all',           'starts_at'=>$now->copy()->subDays(150),'expires_at'=>$now->copy()->subDays(145),'combine_with_other_coupons'=>false,'combine_with_promotions'=>false,'is_active'=>false],
        ];

        foreach ($coupons as $data) {
            Coupon::firstOrCreate(['code' => $data['code']], $data);
        }

        $total  = Coupon::count();
        $active = Coupon::active()->count();
        $this->command->info("✅ Coupons seeded — {$total} total | {$active} active");
        $this->command->info('   Types: Percentage(5) | Fixed(3) | Free Ship(2) | BxGy(2) | Expired(2)');
    }
}
