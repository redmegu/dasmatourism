<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('story_chapters', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('chapter_number');
            $table->text('content');
            $table->string('background_image')->nullable();
            $table->foreignId('attraction_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('story_chapters');
    }
};
