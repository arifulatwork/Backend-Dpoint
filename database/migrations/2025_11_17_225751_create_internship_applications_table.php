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
        Schema::create('internship_applications', function (Blueprint $table) {
            $table->id();
             $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->foreignId('company_id')
                  ->constrained('internship_companies')
                  ->onDelete('cascade');

            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedTinyInteger('duration_months');
            
            $table->json('selected_services');    // array of service slugs
            $table->json('accepted_conditions');  // array of condition slugs

            $table->decimal('total_price', 8, 2);
            $table->string('currency', 3)->default('EUR');

            $table->string('cv_path');           // storage path for CV
            $table->string('status')->default('pending'); // pending, paid, cancelled...

            $table->string('stripe_payment_intent_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_applications');
    }
};
