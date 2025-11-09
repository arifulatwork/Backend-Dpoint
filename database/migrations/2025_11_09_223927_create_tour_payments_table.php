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
        Schema::create('tour_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_booking_id')->constrained('tour_bookings')->onDelete('cascade');

            // generic payment provider info
            $table->string('provider')->index(); // e.g. 'stripe', 'paypal'
            $table->decimal('amount', 10, 2);
            $table->char('currency', 3)->default('EUR');

            // provider references
            $table->string('provider_intent_id')->nullable()->index();
            $table->string('provider_payment_id')->nullable()->index();

            $table->enum('status', [
                'requires_payment_method','requires_confirmation','requires_action',
                'processing','succeeded','failed','canceled','refunded','partially_refunded'
            ])->default('processing')->index();

            $table->decimal('refunded_amount', 10, 2)->default(0);
            $table->string('receipt_url')->nullable();
            $table->json('provider_payload')->nullable(); // store Stripe/PayPal raw response

            $table->timestamps();

            $table->index(['provider','status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_payments');
    }
};
