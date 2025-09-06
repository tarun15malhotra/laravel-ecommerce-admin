<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        $gender = $this->faker->randomElement(['male', 'female', 'other']);
        $firstName = $this->faker->firstName($gender === 'other' ? null : $gender);
        $lastName = $this->faker->lastName();
        $emailVerified = $this->faker->boolean(80);
        
        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'date_of_birth' => $this->faker->dateTimeBetween('-60 years', '-18 years'),
            'gender' => $gender,
            'password' => Hash::make('password'),
            'is_active' => $this->faker->boolean(95),
            'email_verified' => $emailVerified,
            'email_verified_at' => $emailVerified ? now() : null,
            'total_spent' => 0,
            'order_count' => 0,
            'customer_group' => $this->faker->randomElement(['regular', 'vip', 'wholesale', 'retail']),
            'preferences' => [
                'newsletter' => $this->faker->boolean(60),
                'sms_notifications' => $this->faker->boolean(40),
                'marketing_emails' => $this->faker->boolean(50),
            ],
            'last_order_at' => null,
            'remember_token' => null,
        ];
    }

    /**
     * Indicate that the customer is verified.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified' => true,
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Indicate that the customer is unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified' => false,
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the customer is a VIP.
     */
    public function vip(): static
    {
        return $this->state(fn (array $attributes) => [
            'customer_group' => 'vip',
            'total_spent' => $this->faker->randomFloat(2, 5000, 50000),
            'order_count' => $this->faker->numberBetween(10, 100),
        ]);
    }

    /**
     * Indicate that the customer is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the customer has order statistics.
     */
    public function withOrders(): static
    {
        $orderCount = $this->faker->numberBetween(1, 50);
        $totalSpent = $this->faker->randomFloat(2, 100, 10000);
        
        return $this->state(fn (array $attributes) => [
            'order_count' => $orderCount,
            'total_spent' => $totalSpent,
            'last_order_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ]);
    }
}
