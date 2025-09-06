<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
        $superAdmin->assignRole('super-admin');

        // Create Manager
        $manager = User::create([
            'name' => 'John Manager',
            'email' => 'manager@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
        $manager->assignRole('manager');

        // Create Staff Users
        $staff1 = User::create([
            'name' => 'Jane Staff',
            'email' => 'staff1@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
        $staff1->assignRole('staff');

        $staff2 = User::create([
            'name' => 'Bob Staff',
            'email' => 'staff2@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
        $staff2->assignRole('staff');

        // Create Customer Support
        $support = User::create([
            'name' => 'Alice Support',
            'email' => 'support@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
        $support->assignRole('customer-support');

        // Create additional random users
        User::factory(5)->create()->each(function ($user) {
            $user->assignRole('staff');
        });
    }
}
