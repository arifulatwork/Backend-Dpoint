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
        Schema::create('student_intake_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('full_name');
            $table->string('email');
            $table->string('contact_phone')->nullable();
            $table->string('nationality');
            $table->string('target_country')->nullable();
            $table->string('current_situation')->nullable();
            $table->date('visa_expiry_date')->nullable();
            $table->string('has_residence_card')->nullable();

            $table->json('services_needed')->nullable();
            $table->text('professional_info')->nullable();
            $table->string('future_plans')->nullable();

            $table->json('document_paths')->nullable();

            $table->integer('amount_cents');
            $table->string('currency', 8)->default('eur');
            $table->string('status', 32)->default('pending_payment');
            $table->string('stripe_payment_intent_id')->nullable();

            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_intake_submissions');
    }
};
