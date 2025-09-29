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
        Schema::create('internship_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('enrollment_id')
                ->constrained('internship_enrollments')
                ->cascadeOnDelete();

            // Payment identifiers
            // PI may be null when using Stripe Checkout (before payment is completed)
            $table->string('stripe_payment_intent_id')->nullable();
            // Checkout Session ID is present immediately for Checkout flow
            $table->string('stripe_checkout_session_id')->nullable();

            // Money
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('eur');

            // State
            $table->enum('status', ['pending','processing','succeeded','failed','refunded'])
                ->default('pending');

            // Raw Stripe payloads (session/intent objects)
            $table->json('stripe_response')->nullable();

            $table->timestamps();

            // Helpful indexes
            $table->index('enrollment_id');
            $table->index('status');

            // Safe uniques (multiple NULLs allowed)
            $table->unique('stripe_payment_intent_id', 'uniq_pi');
            $table->unique('stripe_checkout_session_id', 'uniq_cs');

            // ⛔️ DO NOT use the old composite unique with PI; it breaks when PI is empty.
            // $table->unique(['enrollment_id','stripe_payment_intent_id'], 'uniq_enroll_pi'); // removed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_payments');
    }
};
