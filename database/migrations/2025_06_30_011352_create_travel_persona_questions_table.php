<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('travel_persona_questions', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();        // e.g., travelReason, environment
            $table->string('text');                 // question text
            $table->boolean('multiple')->default(false);
            $table->boolean('has_budget_slider')->default(false);
            $table->timestamps();

            $table->index('key'); // optional (unique already indexes it)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('travel_persona_questions');
    }
};
