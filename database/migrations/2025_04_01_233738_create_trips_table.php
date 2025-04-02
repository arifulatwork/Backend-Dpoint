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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('trip_categories');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('price', 8, 2);
            $table->decimal('original_price', 8, 2);
            $table->integer('discount_percentage');
            $table->string('image_url');
            $table->integer('duration_days');
            $table->integer('max_participants')->nullable();
            $table->json('highlights')->nullable();
            $table->json('learning_outcomes')->nullable();
            $table->json('personal_development')->nullable();
            $table->json('certifications')->nullable();
            $table->json('environmental_impact')->nullable();
            $table->json('community_benefits')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
