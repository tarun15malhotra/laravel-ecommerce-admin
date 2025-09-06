<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition(): array
    {
        $discountType = $this->faker->randomElement(['fixed', 'percentage']);
        $discountValue = $discountType === 'percentage' 
            ? $this->faker->numberBetween(5, 50) 
            : $this->faker->randomFloat(2, 5, 100);
        
        $validFrom = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $validUntil = $this->faker->dateTimeBetween($validFrom, '+3 months');
        
        return [
            'code' => strtoupper($this->faker->unique()->bothify('????##')),
            'description' => $this->faker->sentence(),
            'discount_type' => $discountType,
            'discount_value' => $discountValue,
            'minimum_order_amount' => $this->faker->boolean(60) ? $this->faker->randomFloat(2, 50, 500) : null,
            'usage_limit' => $this->faker->boolean(70) ? $this->faker->numberBetween(10, 1000) : null,
            'usage_limit_per_customer' => $this->faker->boolean(50) ? $this->faker->numberBetween(1, 5) : null,
            'usage_count' => 0,
            'is_active' => $this->faker->boolean(80),
            'valid_from' => $validFrom,
            'valid_until' => $validUntil,
            'applicable_categories' => [],
            'applicable_products' => [],
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
            'valid_from' => now()->subDays(7),
            'valid_until' => now()->addDays(30),
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'valid_from' => now()->subMonths(2),
            'valid_until' => now()->subDays(1),
        ]);
    }

    public function percentage($value = 10): static
    {
        return $this->state(fn (array $attributes) => [
            'discount_type' => 'percentage',
            'discount_value' => min($value, 100),
        ]);
    }

    public function fixed($value = 10): static
    {
        return $this->state(fn (array $attributes) => [
            'discount_type' => 'fixed',
            'discount_value' => $value,
        ]);
    }

    public function withUsageLimit($limit): static
    {
        return $this->state(fn (array $attributes) => [
            'usage_limit' => $limit,
        ]);
    }
}
