<?php

namespace Database\Seeders;

use App\Models\BusinessCategory;
use Illuminate\Database\Seeder;

class BusinessCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Restaurants',
                'slug' => 'restaurants',
                'description' => 'Dining establishments and eateries',
                'icon' => 'bx-restaurant',
                'is_active' => true,
            ],
            [
                'name' => 'Hotels & Accommodation',
                'slug' => 'hotels-accommodation',
                'description' => 'Hotels, inns, and lodging facilities',
                'icon' => 'bx-hotel',
                'is_active' => true,
            ],
            [
                'name' => 'Retail Shops',
                'slug' => 'retail-shops',
                'description' => 'Retail stores and shops',
                'icon' => 'bx-store',
                'is_active' => true,
            ],
            [
                'name' => 'Services',
                'slug' => 'services',
                'description' => 'Professional and personal services',
                'icon' => 'bx-briefcase',
                'is_active' => true,
            ],
            [
                'name' => 'Entertainment',
                'slug' => 'entertainment',
                'description' => 'Entertainment venues and activities',
                'icon' => 'bx-movie',
                'is_active' => true,
            ],
            [
                'name' => 'Healthcare',
                'slug' => 'healthcare',
                'description' => 'Medical facilities and healthcare services',
                'icon' => 'bx-plus-medical',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            BusinessCategory::create($category);
        }
    }
}
