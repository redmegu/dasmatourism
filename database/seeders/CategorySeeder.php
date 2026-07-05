<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Historical Sites',
                'slug' => 'historical-sites',
                'description' => 'Historical landmarks and heritage sites',
                'icon' => 'bx-landmark',
                'is_active' => true,
            ],
            [
                'name' => 'Parks & Recreation',
                'slug' => 'parks-recreation',
                'description' => 'Parks, gardens, and recreational facilities',
                'icon' => 'bx-tree',
                'is_active' => true,
            ],
            [
                'name' => 'Religious Sites',
                'slug' => 'religious-sites',
                'description' => 'Churches, shrines, and places of worship',
                'icon' => 'bx-church',
                'is_active' => true,
            ],
            [
                'name' => 'Museums & Culture',
                'slug' => 'museums-culture',
                'description' => 'Museums and cultural centers',
                'icon' => 'bx-building',
                'is_active' => true,
            ],
            [
                'name' => 'Shopping & Markets',
                'slug' => 'shopping-markets',
                'description' => 'Shopping centers and local markets',
                'icon' => 'bx-shopping-bag',
                'is_active' => true,
            ],
            [
                'name' => 'Food & Dining',
                'slug' => 'food-dining',
                'description' => 'Restaurants and dining establishments',
                'icon' => 'bx-restaurant',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
