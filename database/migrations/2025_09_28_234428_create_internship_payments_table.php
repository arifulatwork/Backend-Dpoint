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
            $table->foreignId('enrollment_id')->constrained('internship_enrollments')->cascadeOnDelete();
            $table->string('stripe_payment_intent_id')->index();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('eur');
            $table->enum('status', ['pending','processing','succeeded','failed','refunded'])->default('pending');
            $table->json('stripe_response')->nullable();
            $table->timestamps();

            $table->unique(['enrollment_id','stripe_payment_intent_id'], 'uniq_enroll_pi');
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
