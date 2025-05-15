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
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('country');
            $table->string('city');
            $table->string('image');

            $table->decimal('latitude', 10, 7)->nullable();   // NEW: for mapping
            $table->decimal('longitude', 10, 7)->nullable();  // NEW: for mapping

            $table->enum('visit_type', ['individual', 'group', 'company'])->default('individual'); // NEW: visit type

            $table->json('highlights')->nullable(); // JSON highlights (optional)
            $table->json('cuisine')->nullable();    // JSON cuisine (optional)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
