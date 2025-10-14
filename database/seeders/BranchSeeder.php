<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Branch::create([
            'name' => 'Downtown Supermarket',
            'code' => 'DT001',
            'address' => '123 Nairobi CBD',
            'phone' => '0700000001',
        ]);

        Branch::create([
            'name' => 'Westlands Supermarket',
            'code' => 'WL001',
            'address' => '45 Westlands Ave',
            'phone' => '0700000002',
        ]);
    }
}
