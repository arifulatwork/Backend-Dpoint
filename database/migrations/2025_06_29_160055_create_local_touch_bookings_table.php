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
        Schema::create('local_touch_bookings', function (Blueprint $table) {
            $table->id();
             $table->foreignId('user_id')->constrained()->onDelete('cascade'); // who booked
    $table->foreignId('experience_id')->constrained()->onDelete('cascade'); // which experience
    $table->date('date');
    $table->time('time');
    $table->unsignedInteger('participants')->default(1);
    $table->text('special_requests')->nullable();
    $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('local_touch_bookings');
    }
};
