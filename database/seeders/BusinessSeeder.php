<?php

namespace Database\Seeders;

use App\Models\Business;
use Illuminate\Database\Seeder;

class BusinessSeeder extends Seeder
{
    public function run(): void
    {
        $businesses = [
            [
                'business_category_id' => 1, // Restaurants
                'owner_id' => 2, // Juan Dela Cruz
                'name' => 'Kusina ni Tita',
                'slug' => 'kusina-ni-tita',
                'description' => 'Authentic Filipino home-cooked meals served in a cozy atmosphere. Specializing in traditional dishes and regional delicacies.',
                'address' => 'Aguinaldo Highway, Poblacion 1B, Dasmariñas, Cavite',
                'contact_number' => '(046) 416-7890',
                'email' => 'kusinanitita@example.com',
                'services' => ['Dine-in', 'Take-out', 'Catering'],
                'operating_hours' => 'Monday-Sunday: 10:00 AM - 9:00 PM',
                'status' => 'approved',
                'is_verified' => true,
                'views' => rand(50, 200),
            ],
            [
                'business_category_id' => 2, // Hotels & Accommodation
                'owner_id' => 3, // Maria Santos
                'name' => 'Dasmariñas Hotel',
                'slug' => 'dasmarinas-hotel',
                'description' => 'Comfortable accommodation with modern amenities. Perfect for business travelers and tourists exploring the city.',
                'address' => 'Congressional Road, Zone IV, Dasmariñas, Cavite',
                'contact_number' => '(046) 416-8901',
                'email' => 'info@dasmhotel.com',
                'website' => 'https://www.dasmhotel.com',
                'services' => ['Rooms', 'Conference facilities', 'Free WiFi', 'Parking'],
                'operating_hours' => '24/7',
                'status' => 'approved',
                'is_verified' => true,
                'views' => rand(50, 200),
            ],
            [
                'business_category_id' => 3, // Retail Shops
                'owner_id' => 2,
                'name' => 'Cavite Souvenirs & Crafts',
                'slug' => 'cavite-souvenirs-crafts',
                'description' => 'Local handicrafts, souvenirs, and traditional products from Cavite. Perfect gifts and memorabilia.',
                'address' => 'Paliparan Road, Dasmariñas, Cavite',
                'contact_number' => '(046) 416-9012',
                'services' => ['Retail', 'Gift wrapping', 'Custom orders'],
                'operating_hours' => 'Monday-Saturday: 9:00 AM - 7:00 PM, Sunday: 10:00 AM - 5:00 PM',
                'status' => 'approved',
                'is_verified' => true,
                'views' => rand(50, 200),
            ],
            [
                'business_category_id' => 1, // Restaurants
                'owner_id' => 3,
                'name' => 'The Coffee Hub',
                'slug' => 'the-coffee-hub',
                'description' => 'Premium coffee shop serving specialty coffee, pastries, and light meals. Perfect spot for meetings and relaxation.',
                'address' => 'Sampaloc Road, Dasmariñas, Cavite',
                'contact_number' => '(046) 416-0123',
                'email' => 'hello@coffeehub.ph',
                'services' => ['Dine-in', 'Take-out', 'WiFi', 'Study area'],
                'operating_hours' => 'Monday-Sunday: 7:00 AM - 10:00 PM',
                'status' => 'approved',
                'is_verified' => false,
                'views' => rand(50, 200),
            ],
        ];

        foreach ($businesses as $businessData) {
            Business::create($businessData);
        }
    }
}
