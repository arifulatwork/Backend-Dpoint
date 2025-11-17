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
        Schema::create('internship_services', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();    // 'cv-enhancement', 'placement', etc.
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);      // in EUR
            $table->decimal('original_price', 8, 2)->nullable();
            $table->boolean('popular')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_services');
    }
};
