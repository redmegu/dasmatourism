<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attractions', function (Blueprint $table) {
            $table->string('youtube_video_url')->nullable()->after('website');
            $table->string('uploaded_video_path')->nullable()->after('youtube_video_url');
            $table->string('video_thumbnail')->nullable()->after('uploaded_video_path');
        });
    }

    public function down(): void
    {
        Schema::table('attractions', function (Blueprint $table) {
            $table->dropColumn(['youtube_video_url', 'uploaded_video_path', 'video_thumbnail']);
        });
    }
};
