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
        Schema::create('attractions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('type'); // E.g., museum, historic site, etc.
            $table->string('duration'); // E.g., "2 hours"
            $table->decimal('price', 8, 2); // Price for individual visit
            $table->decimal('group_price', 8, 2)->nullable(); // Price for group visit
            $table->integer('min_group_size')->nullable(); // Minimum group size
            $table->integer('max_group_size')->nullable(); // Maximum group size
            $table->string('image'); // Image URL for the attraction
            $table->json('highlights')->nullable(); // Optional highlights
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attractions');
    }
};
