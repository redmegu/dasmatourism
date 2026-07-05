<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('story_chapters', function (Blueprint $table) {
            // Add character_models field to store array of character images
            $table->json('character_models')->nullable()->after('visual_images');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('story_chapters', function (Blueprint $table) {
            $table->dropColumn('character_models');
        });
    }
};
