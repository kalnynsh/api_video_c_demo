<?php

namespace Database\Seeders;

use App\Models\Category\Category;
use Illuminate\Database\Seeder;
use Database\Factories\Category\CategoryFactory;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryAmount = (new CategoryFactory)->getCategoryAmount();

        Category::factory()
            ->count($categoryAmount)
            ->create();
    }
}
