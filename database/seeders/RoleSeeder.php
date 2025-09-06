<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Dashboard
            'view dashboard',
            
            // Products
            'view products',
            'create products',
            'edit products',
            'delete products',
            'import products',
            'export products',
            
            // Categories
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            
            // Orders
            'view orders',
            'create orders',
            'edit orders',
            'delete orders',
            'update order status',
            'generate invoices',
            
            // Customers
            'view customers',
            'create customers',
            'edit customers',
            'delete customers',
            'export customers',
            
            // Coupons
            'view coupons',
            'create coupons',
            'edit coupons',
            'delete coupons',
            
            // Reports
            'view reports',
            'export reports',
            
            // Settings
            'view settings',
            'edit settings',
            
            // Users
            'view users',
            'create users',
            'edit users',
            'delete users',
            'assign roles',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Super Admin - gets all permissions
        $superAdmin = Role::create(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Manager - gets most permissions except settings and user management
        $manager = Role::create(['name' => 'manager']);
        $manager->givePermissionTo([
            'view dashboard',
            'view products',
            'create products',
            'edit products',
            'delete products',
            'import products',
            'export products',
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            'view orders',
            'create orders',
            'edit orders',
            'update order status',
            'generate invoices',
            'view customers',
            'create customers',
            'edit customers',
            'export customers',
            'view coupons',
            'create coupons',
            'edit coupons',
            'delete coupons',
            'view reports',
            'export reports',
        ]);

        // Staff - limited permissions
        $staff = Role::create(['name' => 'staff']);
        $staff->givePermissionTo([
            'view dashboard',
            'view products',
            'edit products',
            'view categories',
            'view orders',
            'edit orders',
            'update order status',
            'view customers',
            'view coupons',
            'view reports',
        ]);

        // Customer Support
        $support = Role::create(['name' => 'customer-support']);
        $support->givePermissionTo([
            'view dashboard',
            'view orders',
            'edit orders',
            'update order status',
            'view customers',
            'edit customers',
            'view products',
        ]);
    }
}
