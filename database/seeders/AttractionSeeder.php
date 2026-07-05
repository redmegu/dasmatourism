<?php

namespace Database\Seeders;

use App\Models\Attraction;
use App\Models\AttractionSchedule;
use App\Models\MapMarker;
use Illuminate\Database\Seeder;

class AttractionSeeder extends Seeder
{
    public function run(): void
    {
        $attractions = [
            [
                'category_id' => 1, // Historical Sites
                'name' => 'Dasmariñas City Hall',
                'slug' => 'dasmarinas-city-hall',
                'description' => 'The seat of local government of Dasmariñas City. A modern architectural landmark that serves as the administrative center of the city.',
                'address' => 'Congressional Road, Paliparan III, Dasmariñas, Cavite',
                'latitude' => 14.3294,
                'longitude' => 120.9367,
                'contact_number' => '(046) 416-0931',
                'email' => 'cityofdasmarinas@gmail.com',
                'website' => 'http://dasmarinascity.gov.ph',
                'entrance_fee' => 0,
                'facilities' => 'Parking area, Information desk, Public restrooms',
                'is_historical_site' => false,
                'status' => 'approved',
                'is_featured' => true,
                'views' => rand(100, 500),
                'created_by' => 1,
            ],
            [
                'category_id' => 3, // Religious Sites
                'name' => 'Immaculate Conception Parish Church',
                'slug' => 'immaculate-conception-parish-church',
                'description' => 'A historic Catholic church built in the Spanish colonial era. Known for its beautiful architecture and rich history.',
                'address' => 'Aguinaldo Highway, Poblacion 1A, Dasmariñas, Cavite',
                'latitude' => 14.3275,
                'longitude' => 120.9380,
                'contact_number' => '(046) 416-2345',
                'entrance_fee' => 0,
                'facilities' => 'Chapel, Prayer hall, Parking',
                'is_historical_site' => true,
                'status' => 'approved',
                'is_featured' => true,
                'views' => rand(100, 500),
                'created_by' => 1,
            ],
            [
                'category_id' => 2, // Parks & Recreation
                'name' => 'Dasmariñas City Sports Complex',
                'slug' => 'dasmarinas-city-sports-complex',
                'description' => 'A modern sports facility featuring various athletic amenities including basketball courts, tennis courts, and a swimming pool.',
                'address' => 'Zone IV, Dasmariñas, Cavite',
                'latitude' => 14.3310,
                'longitude' => 120.9350,
                'contact_number' => '(046) 416-3456',
                'entrance_fee' => 50,
                'facilities' => 'Basketball courts, Tennis courts, Swimming pool, Parking, Locker rooms',
                'is_historical_site' => false,
                'status' => 'approved',
                'is_featured' => true,
                'views' => rand(100, 500),
                'created_by' => 1,
            ],
            [
                'category_id' => 5, // Shopping & Markets
                'name' => 'SM City Dasmariñas',
                'slug' => 'sm-city-dasmarinas',
                'description' => 'A major shopping mall in Dasmariñas offering a wide variety of shops, restaurants, and entertainment options.',
                'address' => 'Congressional Avenue Extension, Sampaloc 1, Dasmariñas, Cavite',
                'latitude' => 14.3100,
                'longitude' => 120.9550,
                'contact_number' => '(046) 481-5555',
                'website' => 'https://www.smsupermalls.com/mall/sm-city-dasmarinas',
                'entrance_fee' => 0,
                'facilities' => 'Shops, Restaurants, Cinema, Parking, ATM',
                'is_historical_site' => false,
                'status' => 'approved',
                'is_featured' => false,
                'views' => rand(100, 500),
                'created_by' => 1,
            ],
            [
                'category_id' => 2, // Parks & Recreation
                'name' => 'De La Salle University - Dasmariñas',
                'slug' => 'dlsu-dasmarinas',
                'description' => 'A prestigious university campus with beautiful grounds and modern facilities. Known for academic excellence and innovation.',
                'address' => 'Dasmariñas-Cavite Expressway, Paliparan, Dasmariñas, Cavite',
                'latitude' => 14.2976,
                'longitude' => 120.9389,
                'contact_number' => '(046) 481-1900',
                'website' => 'https://www.dlsud.edu.ph',
                'entrance_fee' => 0,
                'facilities' => 'Library, Sports facilities, Cafeteria, Chapel, Parking',
                'is_historical_site' => false,
                'status' => 'approved',
                'is_featured' => false,
                'views' => rand(100, 500),
                'created_by' => 1,
            ],
        ];

        foreach ($attractions as $attractionData) {
            $attraction = Attraction::create($attractionData);

            // Create map marker
            MapMarker::create([
                'attraction_id' => $attraction->id,
                'latitude' => $attraction->latitude,
                'longitude' => $attraction->longitude,
                'marker_color' => $attraction->is_historical_site ? '#8B4513' : '#FF0000',
                'is_visible' => true,
            ]);

            // Create schedules (Mon-Fri)
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
            foreach ($days as $day) {
                AttractionSchedule::create([
                    'attraction_id' => $attraction->id,
                    'day_of_week' => $day,
                    'opening_time' => '08:00',
                    'closing_time' => '17:00',
                    'is_closed' => false,
                ]);
            }

            // Saturday
            AttractionSchedule::create([
                'attraction_id' => $attraction->id,
                'day_of_week' => 'saturday',
                'opening_time' => '09:00',
                'closing_time' => '15:00',
                'is_closed' => false,
            ]);

            // Sunday (closed)
            AttractionSchedule::create([
                'attraction_id' => $attraction->id,
                'day_of_week' => 'sunday',
                'opening_time' => '00:00',
                'closing_time' => '00:00',
                'is_closed' => true,
            ]);
        }
    }
}
