<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('points_of_interest', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('type', [
                'event', 'hotel', 'restaurant', 'park', 
                'museum', 'attraction', 'activity', 
                'flight', 'shuttle'
            ]);
            $table->json('position')->comment('Geo coordinates as [latitude, longitude]');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->float('rating')->nullable();
            $table->string('price')->nullable();
            $table->string('booking_url')->nullable();
            $table->json('amenities')->nullable();
            $table->json('flight_details')->nullable();
            $table->json('shuttle_details')->nullable();
            $table->timestamps();

            // Virtual columns for latitude and longitude
            $table->double('latitude')->virtualAs('JSON_UNQUOTE(JSON_EXTRACT(position, "$[0]"))');
            $table->double('longitude')->virtualAs('JSON_UNQUOTE(JSON_EXTRACT(position, "$[1]"))');

            // Now add indexes
            $table->index('latitude');
            $table->index('longitude');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('points_of_interest');
    }
};
