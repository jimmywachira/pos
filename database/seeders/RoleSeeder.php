<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $roles = ['Admin', 'Manager', 'Cashier'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        Permission::firstOrCreate(['name' => 'process sales']);
        Permission::firstOrCreate(['name' => 'manage inventory']);
        Permission::firstOrCreate(['name' => 'view reports']);
    }
}
