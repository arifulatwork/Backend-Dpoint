<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('travel_persona_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')
                  ->constrained('travel_persona_questions')
                  ->onDelete('cascade');

            $table->string('value');                // e.g., peace, exploration
            $table->string('label');                // display label
            $table->text('description')->nullable();
            $table->string('emoji', 16)->nullable();
            $table->string('icon', 64)->nullable(); // lucide icon name, etc.
            $table->timestamps();

            $table->unique(['question_id', 'value']); // no duplicate options per question
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('travel_persona_options');
    }
};
