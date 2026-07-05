<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('map_markers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attraction_id')->constrained()->onDelete('cascade');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('marker_icon')->nullable();
            $table->string('marker_color')->default('#FF0000');
            $table->integer('zoom_level')->default(15);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('map_markers');
    }
};
