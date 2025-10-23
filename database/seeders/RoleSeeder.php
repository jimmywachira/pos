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

        // Define Permissions
        $permissions = [
            'pos.access', 'pos.process-sales',
            'inventory.view-products', 'inventory.manage-products',
            'inventory.view-batches', 'inventory.adjust-stock',
            'customers.view', 'customers.manage',
            'reports.view-sales',
            'settings.manage-general',
            'users.manage', // For managing employees
            'audits.view', // For viewing audit logs
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Define Roles and Assign Permissions
        $cashier = Role::firstOrCreate(['name' => 'Cashier']);
        $cashier->givePermissionTo(['pos.access', 'pos.process-sales', 'customers.view']);

        $inventoryClerk = Role::firstOrCreate(['name' => 'Inventory Clerk']);
        $inventoryClerk->givePermissionTo([
            'inventory.view-products', 'inventory.manage-products',
            'inventory.view-batches', 'inventory.adjust-stock'
        ]);

        $manager = Role::firstOrCreate(['name' => 'Manager']);
        $manager->givePermissionTo([
            'pos.access', 'pos.process-sales',
            'inventory.view-products', 'inventory.manage-products',
            'inventory.view-batches', 'inventory.adjust-stock',
            'customers.view', 'customers.manage',
            'reports.view-sales',
        ]);

        $auditor = Role::firstOrCreate(['name' => 'Auditor']);
        $auditor->givePermissionTo(['reports.view-sales', 'audits.view']);

        // Admin gets all permissions
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->givePermissionTo(Permission::all());
    }
}

