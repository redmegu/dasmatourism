<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('landmark_suggestions', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('address');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->json('images')->nullable()->after('longitude');
            $table->boolean('is_historical')->default(false)->after('images');
        });
    }

    public function down(): void
    {
        Schema::table('landmark_suggestions', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'images', 'is_historical']);
        });
    }
};
