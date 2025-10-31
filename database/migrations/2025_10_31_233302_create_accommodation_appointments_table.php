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
        Schema::create('accommodation_appointments', function (Blueprint $table) {
            $table->id();
             $table->foreignId('point_of_interest_id')
                  ->constrained('points_of_interest')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            // If you use auth: store who booked it (nullable if guest bookings allowed)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->date('appointment_date'); // check-in
            $table->date('end_date');         // check-out
            $table->unsignedInteger('number_of_guests')->default(1);
            $table->text('special_requests')->nullable();
            $table->json('appointment_details')->nullable(); // { room_type, etc. }

            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');

            $table->timestamps();

            // Simple safeguard: prevent exact duplicate for same POI & dates
            $table->unique(['point_of_interest_id', 'appointment_date', 'end_date'], 'poi_dates_unique');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accommodation_appointments');
    }
};
