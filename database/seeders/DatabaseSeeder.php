<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call individual seeders
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            TagSeeder::class,
            ProductSeeder::class,
            CustomerSeeder::class,
            CouponSeeder::class,
            OrderSeeder::class,
            SettingsSeeder::class,
        ]);
    }
}
