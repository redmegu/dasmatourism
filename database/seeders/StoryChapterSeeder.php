<?php

namespace Database\Seeders;

use App\Models\StoryChapter;
use App\Models\StoryChoice;
use Illuminate\Database\Seeder;

class StoryChapterSeeder extends Seeder
{
    public function run(): void
    {
        // Chapter 1
        $chapter1 = StoryChapter::create([
            'title' => 'Welcome to Dasmariñas',
            'chapter_number' => 1,
            'content' => 'Welcome to Dasmariñas City! You are about to embark on a virtual journey through the rich history and vibrant culture of this beautiful city. As you walk through the main plaza, you notice the historic city hall ahead of you. Where would you like to go first?',
            'attraction_id' => 1,
            'is_active' => true,
        ]);

        // Chapter 2
        $chapter2 = StoryChapter::create([
            'title' => 'The Historic Church',
            'chapter_number' => 2,
            'content' => 'You approach the magnificent Immaculate Conception Parish Church. Its Spanish colonial architecture stands as a testament to centuries of faith and history. The bells ring softly in the distance. Would you like to explore inside or visit the nearby market?',
            'attraction_id' => 2,
            'is_active' => true,
        ]);

        // Chapter 3
        $chapter3 = StoryChapter::create([
            'title' => 'Modern Dasmariñas',
            'chapter_number' => 3,
            'content' => 'The city has grown tremendously. You find yourself at the bustling SM City Dasmariñas, where modern life meets local culture. The aroma of local delicacies fills the air. What would you like to experience?',
            'attraction_id' => 4,
            'is_active' => true,
        ]);

        // Create choices for Chapter 1
        StoryChoice::create([
            'chapter_id' => $chapter1->id,
            'choice_text' => 'Visit the historic church',
            'next_chapter_id' => $chapter2->id,
            'order' => 1,
        ]);

        StoryChoice::create([
            'chapter_id' => $chapter1->id,
            'choice_text' => 'Explore the modern shopping district',
            'next_chapter_id' => $chapter3->id,
            'order' => 2,
        ]);

        // Create choices for Chapter 2
        StoryChoice::create([
            'chapter_id' => $chapter2->id,
            'choice_text' => 'Explore the modern side of the city',
            'next_chapter_id' => $chapter3->id,
            'order' => 1,
        ]);

        StoryChoice::create([
            'chapter_id' => $chapter2->id,
            'choice_text' => 'End your journey here',
            'next_chapter_id' => null,
            'order' => 2,
        ]);

        // Create choices for Chapter 3
        StoryChoice::create([
            'chapter_id' => $chapter3->id,
            'choice_text' => 'Complete your tour',
            'next_chapter_id' => null,
            'order' => 1,
        ]);
    }
}
