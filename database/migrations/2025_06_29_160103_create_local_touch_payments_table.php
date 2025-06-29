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
        Schema::create('local_touch_payments', function (Blueprint $table) {
            $table->id();
             $table->foreignId('booking_id')->constrained('local_touch_bookings')->onDelete('cascade');
    $table->string('stripe_payment_intent_id');
    $table->string('stripe_payment_method')->nullable();
    $table->decimal('amount', 10, 2);
    $table->enum('status', ['pending', 'succeeded', 'failed'])->default('pending');
    $table->json('payment_details')->nullable(); // optional for full Stripe response
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('local_touch_payments');
    }
};
