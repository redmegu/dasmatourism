<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\Attraction;
use App\Models\Business;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $attractions = Attraction::all();
        $businesses = Business::all();

        // Reviews for Attractions
        foreach ($attractions as $attraction) {
            for ($i = 0; $i < rand(3, 8); $i++) {
                Review::create([
                    'user_id' => rand(4, 13), // Regular users
                    'reviewable_type' => Attraction::class,
                    'reviewable_id' => $attraction->id,
                    'rating' => rand(3, 5),
                    'comment' => $this->getRandomComment(),
                    'status' => 'approved',
                    'approved_by' => 1,
                    'approved_at' => now(),
                ]);
            }
        }

        // Reviews for Businesses
        foreach ($businesses as $business) {
            for ($i = 0; $i < rand(2, 6); $i++) {
                Review::create([
                    'user_id' => rand(4, 13),
                    'reviewable_type' => Business::class,
                    'reviewable_id' => $business->id,
                    'rating' => rand(3, 5),
                    'comment' => $this->getRandomComment(),
                    'status' => 'approved',
                    'approved_by' => 1,
                    'approved_at' => now(),
                ]);
            }
        }
    }

    private function getRandomComment(): string
    {
        $comments = [
            'Amazing place! Highly recommended!',
            'Great experience, will definitely come back.',
            'Beautiful location and friendly staff.',
            'Worth the visit. Very well maintained.',
            'Excellent service and atmosphere.',
            'A must-visit destination in Dasmariñas!',
            'Perfect for family outings.',
            'Clean and organized facility.',
            'Good value for money.',
            'Enjoyed our time here!',
        ];

        return $comments[array_rand($comments)];
    }
}
