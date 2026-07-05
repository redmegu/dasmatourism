<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            BusinessCategorySeeder::class,
            UserSeeder::class,
            AttractionSeeder::class,
            BusinessSeeder::class,
            ReviewSeeder::class,
            StoryChapterSeeder::class,
        ]);
    }
}
