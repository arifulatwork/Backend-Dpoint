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
        Schema::create('travel_persona_questions', function (Blueprint $table) {
            $table->id();
             $table->string('question_id'); // e.g., travelReason, environment
    $table->string('text');
    $table->boolean('multiple')->default(false);
    $table->boolean('has_budget_slider')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_persona_questions');
    }
};
