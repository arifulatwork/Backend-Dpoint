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
        Schema::create('points_of_interest', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained()->onDelete('cascade');

            $table->string('name');
            $table->enum('type', [
                'event',
                'hotel', 
                'restaurant', 
                'park', 
                'museum', 
                'attraction', 
                'activity', 
                'flight', 
                'shuttle'
            ]);

            $table->decimal('latitude', 10, 8);  // precise lat
            $table->decimal('longitude', 11, 8); // precise lng

            $table->text('description')->nullable();
            $table->string('image')->nullable(); // image URL

            $table->float('rating')->nullable(); // e.g., 4.5
            $table->string('price')->nullable(); // e.g., "€€" or "$$"

            $table->string('booking_url')->nullable(); // if bookable

            $table->json('amenities')->nullable(); // amenities for hotels, restaurants etc.

            $table->json('flight_details')->nullable(); // only for flight POIs
            // Example JSON: { "departure": "Barcelona", "arrival": "Madrid", "airline": "Iberia", "flight_number": "IB1234" }

            $table->json('shuttle_details')->nullable(); // only for shuttle POIs
            // Example JSON: { "frequency": "Every 30 minutes", "capacity": 50, "duration": "45 min" }

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('points_of_interest');
    }
};
