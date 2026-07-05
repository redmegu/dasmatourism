<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('story_choices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained('story_chapters')->onDelete('cascade');
            $table->text('choice_text');
            $table->foreignId('next_chapter_id')->nullable()->constrained('story_chapters')->onDelete('set null');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('story_choices');
    }
};
