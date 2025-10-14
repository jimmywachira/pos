<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $food = Category::create(['name' => 'Food & Beverages']);
        $household = Category::create(['name' => 'Household']);
        $personal = Category::create(['name' => 'Personal Care']);

        Category::create(['name' => 'Snacks', 'parent_id' => $food->id]);
        Category::create(['name' => 'Cleaning Supplies', 'parent_id' => $household->id]);
        Category::create(['name' => 'Health & Beauty', 'parent_id' => $personal->id]);
    }
}
