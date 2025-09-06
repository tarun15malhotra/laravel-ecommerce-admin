<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create VIP customers with order history
        Customer::factory(10)
            ->vip()
            ->verified()
            ->withOrders(rand(10, 30), rand(5000, 20000))
            ->create();

        // Create regular verified customers with some orders
        Customer::factory(30)
            ->verified()
            ->withOrders(rand(1, 10), rand(100, 5000))
            ->create();

        // Create new customers without orders
        Customer::factory(20)
            ->verified()
            ->create();

        // Create unverified customers
        Customer::factory(10)
            ->unverified()
            ->create();

        // Create inactive customers
        Customer::factory(5)
            ->inactive()
            ->withOrders(rand(1, 5), rand(100, 1000))
            ->create();
    }
}
