<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::table('promotions')
            ->where('promotable_type', 'Business')
            ->update(['promotable_type' => 'App\\Models\\Business']);
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
