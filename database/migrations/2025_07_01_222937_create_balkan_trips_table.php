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
        Schema::create('balkan_trips', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('duration'); // e.g., '15 days'
            $table->decimal('price', 10, 2);
            $table->string('image_url');
            $table->json('destinations'); // e.g., ["Albania", "Greece"]
            $table->json('group_size');   // e.g., { "min": 4, "max": 12 }
            $table->json('itinerary');    // Full day-by-day array
            $table->json('included');     // List of included services
            $table->json('not_included'); // List of excluded services
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balkan_trips');
    }
};
