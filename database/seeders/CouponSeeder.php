<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create active percentage coupons
        Coupon::factory(5)
            ->active()
            ->percentage(10)
            ->create();

        Coupon::factory(3)
            ->active()
            ->percentage(20)
            ->withUsageLimit(100)
            ->create();

        // Create active fixed amount coupons
        Coupon::factory(5)
            ->active()
            ->fixed(15)
            ->create();

        Coupon::factory(2)
            ->active()
            ->fixed(50)
            ->withUsageLimit(50)
            ->create();

        // Create some expired coupons for testing
        Coupon::factory(5)
            ->expired()
            ->create();

        // Create special promotional coupons
        Coupon::create([
            'code' => 'WELCOME10',
            'description' => 'Welcome discount for new customers',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'minimum_order_amount' => 50,
            'usage_limit' => 1000,
            'usage_limit_per_customer' => 1,
            'usage_count' => 0,
            'is_active' => true,
            'valid_from' => now(),
            'valid_until' => now()->addMonths(3),
        ]);

        Coupon::create([
            'code' => 'SUMMER25',
            'description' => 'Summer sale - 25% off',
            'discount_type' => 'percentage',
            'discount_value' => 25,
            'minimum_order_amount' => 100,
            'usage_limit' => 500,
            'usage_limit_per_customer' => 2,
            'usage_count' => 0,
            'is_active' => true,
            'valid_from' => now(),
            'valid_until' => now()->addMonths(2),
        ]);

        Coupon::create([
            'code' => 'FREESHIP',
            'description' => 'Free shipping on orders over $75',
            'discount_type' => 'fixed',
            'discount_value' => 10,
            'minimum_order_amount' => 75,
            'usage_limit' => null,
            'usage_limit_per_customer' => null,
            'usage_count' => 0,
            'is_active' => true,
            'valid_from' => now(),
            'valid_until' => now()->addMonth(),
        ]);
    }
}
