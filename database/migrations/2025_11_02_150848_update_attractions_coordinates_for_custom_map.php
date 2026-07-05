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
        Schema::table('attractions', function (Blueprint $table) {
            // Change latitude and longitude to support 0-1000 range for custom map
            $table->decimal('latitude', 6, 2)->change();  // 0-1000.00
            $table->decimal('longitude', 6, 2)->change(); // 0-1000.00
        });

        Schema::table('map_markers', function (Blueprint $table) {
            // Update map_markers table as well
            $table->decimal('latitude', 6, 2)->change();  // 0-1000.00
            $table->decimal('longitude', 6, 2)->change(); // 0-1000.00
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attractions', function (Blueprint $table) {
            // Revert back to GPS coordinates range
            $table->decimal('latitude', 10, 8)->change();  // GPS latitude
            $table->decimal('longitude', 11, 8)->change(); // GPS longitude
        });

        Schema::table('map_markers', function (Blueprint $table) {
            // Revert map_markers table
            $table->decimal('latitude', 10, 8)->change();  // GPS latitude
            $table->decimal('longitude', 11, 8)->change(); // GPS longitude
        });
    }
};
