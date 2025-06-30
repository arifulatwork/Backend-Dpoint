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
        Schema::create('travel_persona_options', function (Blueprint $table) {
            $table->id();
             $table->foreignId('question_id')->constrained('travel_persona_questions')->onDelete('cascade');
    $table->string('value');
    $table->string('label');
    $table->string('description')->nullable();
    $table->string('emoji')->nullable();
    $table->string('icon')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_persona_options');
    }
};
