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
        Schema::create('guides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attraction_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('avatar')->nullable(); // URL to the guide's avatar
            $table->decimal('rating', 3, 2); // Guide's rating (e.g., 4.5)
            $table->integer('reviews'); // Number of reviews
            $table->string('experience'); // E.g., "5 years of experience"
            $table->json('languages'); // Languages spoken by the guide
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guides');
    }
};
