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
        Schema::create('internships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('internship_categories')->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('duration');
            $table->decimal('price', 8, 2);
            $table->decimal('original_price', 8, 2)->nullable();
            $table->decimal('rating', 2, 1)->default(0);
            $table->integer('review_count')->default(0);
            $table->string('company');
            $table->string('location');
            $table->enum('mode', ['remote', 'on-site', 'hybrid']);
            $table->string('image')->nullable();
            $table->boolean('featured')->default(false);
            $table->date('deadline')->nullable();
            $table->integer('spots_left')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internships');
    }
};
