<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $customerId = Customer::inRandomOrder()->first()?->id;
        $status = $this->faker->randomElement(['pending', 'processing', 'shipped', 'delivered', 'cancelled']);
        $paymentStatus = $status === 'cancelled' ? 'failed' : $this->faker->randomElement(['pending', 'paid']);
        
        $subtotal = $this->faker->randomFloat(2, 50, 1000);
        $taxAmount = $subtotal * 0.1;
        $shippingAmount = $this->faker->randomElement([0, 5.99, 10.99, 15.99]);
        $discountAmount = $this->faker->boolean(20) ? $this->faker->randomFloat(2, 5, 50) : 0;
        $totalAmount = $subtotal + $taxAmount + $shippingAmount - $discountAmount;
        
        $address = [
            'street' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'country' => $this->faker->country(),
            'postal_code' => $this->faker->postcode(),
        ];
        
        $createdAt = $this->faker->dateTimeBetween('-6 months', 'now');
        
        return [
            'order_number' => 'ORD-' . $this->faker->unique()->numberBetween(100000, 999999),
            'customer_id' => $customerId,
            'status' => $status,
            'payment_status' => $paymentStatus,
            'payment_method' => $this->faker->randomElement(['cod', 'credit_card', 'paypal', 'bank_transfer']),
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'shipping_amount' => $shippingAmount,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'coupon_code' => $this->faker->boolean(20) ? strtoupper($this->faker->bothify('SAVE##')) : null,
            'shipping_address' => $address,
            'billing_address' => $this->faker->boolean(80) ? $address : [
                'street' => $this->faker->streetAddress(),
                'city' => $this->faker->city(),
                'state' => $this->faker->state(),
                'country' => $this->faker->country(),
                'postal_code' => $this->faker->postcode(),
            ],
            'shipping_method' => $this->faker->randomElement(['standard', 'express', 'overnight']),
            'tracking_number' => $status === 'shipped' || $status === 'delivered' ? strtoupper($this->faker->bothify('??#########')) : null,
            'notes' => $this->faker->boolean(20) ? $this->faker->sentence() : null,
            'admin_notes' => $this->faker->boolean(10) ? $this->faker->sentence() : null,
            'shipped_at' => $status === 'shipped' || $status === 'delivered' ? (clone $createdAt)->modify('+' . rand(1, 3) . ' days') : null,
            'delivered_at' => $status === 'delivered' ? (clone $createdAt)->modify('+' . rand(3, 7) . ' days') : null,
            'created_at' => $createdAt,
            'updated_at' => (clone $createdAt)->modify('+' . rand(1, 48) . ' hours'),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'payment_status' => 'pending',
            'shipped_at' => null,
            'delivered_at' => null,
            'tracking_number' => null,
        ]);
    }

    public function delivered(): static
    {
        return $this->state(function (array $attributes) {
            $createdAt = $this->faker->dateTimeBetween('-60 days', '-7 days');
            $shippedAt = (clone $createdAt)->modify('+' . rand(1, 3) . ' days');
            $deliveredAt = (clone $createdAt)->modify('+' . rand(3, 7) . ' days');
            
            return [
                'status' => 'delivered',
                'shipped_at' => $shippedAt,
                'delivered_at' => $deliveredAt,
                'created_at' => $createdAt,
                'updated_at' => $deliveredAt,
            ];
        });
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
            'payment_status' => 'failed',
            'shipped_at' => null,
            'delivered_at' => null,
            'tracking_number' => null,
        ]);
    }
}
