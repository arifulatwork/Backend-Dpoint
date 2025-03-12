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
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['food', 'music', 'craft']);
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 8, 2);
            $table->float('rating');
            $table->integer('reviews');
            $table->string('location');
            $table->string('duration');
            $table->integer('max_participants');
            $table->string('image');
            $table->string('city');
            $table->json('host'); // Store host details as JSON
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
