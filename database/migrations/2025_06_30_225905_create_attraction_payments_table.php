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
        Schema::create('attraction_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('attraction_bookings')->onDelete('cascade');
            $table->string('payment_intent_id');
            $table->string('payment_method');
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('eur');
            $table->string('status'); // succeeded, failed, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attraction_payments');
    }
};
