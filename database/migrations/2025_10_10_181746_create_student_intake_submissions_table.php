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

            // Basic info (unchanged)
            $table->string('full_name');
            $table->string('email');
            $table->string('contact_phone')->nullable();
            $table->string('nationality');

            // NEW: Current location field
            $table->string('current_location');

            // REMOVED: Target country and current situation (replaced by new fields)
            // $table->string('target_country')->nullable();
            // $table->string('current_situation')->nullable();

            // UPDATED: Visa status fields
            $table->string('visa_status'); // ✅ I have valid visa, ⏳ expires soon, ❌ no visa
            $table->date('visa_expiry_date')->nullable();

            // UPDATED: Residence document field
            $table->string('has_residence_card'); // yes, no, in_process

            // NEW: Student status field
            $table->string('student_status'); // current_student, finished_bachelor, etc.

            // NEW: Accommodation and insurance fields
            $table->string('has_accommodation')->nullable(); // yes, no
            $table->string('has_health_insurance')->nullable(); // yes, no
            $table->string('has_empadronamiento')->nullable(); // yes, no

            // UPDATED: Services needed (new options)
            $table->json('services_needed')->nullable();

            // UPDATED: Renamed from professional_info to additional_info
            $table->text('additional_info')->nullable();

            // REMOVED: Future plans field (replaced by student_status and services)
            // $table->string('future_plans')->nullable();

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
