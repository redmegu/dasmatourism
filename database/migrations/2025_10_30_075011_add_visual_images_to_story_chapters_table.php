<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('story_chapters', function (Blueprint $table) {
            $table->json('visual_images')->nullable()->after('background_image');
        });
    }

    public function down(): void
    {
        Schema::table('story_chapters', function (Blueprint $table) {
            $table->dropColumn('visual_images');
        });
    }
};
