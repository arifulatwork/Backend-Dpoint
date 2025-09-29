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
        Schema::create('internship_enrollments', function (Blueprint $table) {
            $table->id();

            // 🔗 Relations
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('internship_id')
                ->constrained()
                ->cascadeOnDelete();

            // 💳 Payment / Stripe
            $table->string('stripe_payment_intent_id')->nullable()->unique();
            $table->string('stripe_customer_id')->nullable();
            $table->decimal('amount', 10, 2);              // in EUR
            $table->string('currency', 3)->default('eur'); // default EUR

            $table->enum('status', [
                'pending',
                'processing',
                'succeeded',
                'failed',
                'canceled',
            ])->default('pending');

            $table->json('payment_details')->nullable(); // payment method, last4, etc.
            $table->timestamp('enrolled_at')->nullable();
            $table->timestamp('payment_completed_at')->nullable();
            $table->text('failure_message')->nullable();

            $table->timestamps();

            // 🔒 Prevent same user enrolling twice
            $table->unique(['user_id', 'internship_id'], 'uniq_user_internship');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_enrollments');
    }
};
