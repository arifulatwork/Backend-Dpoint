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
        Schema::create('internship_locations', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();     // 'spain', 'uk', etc.
            $table->string('country');
            $table->json('cities');               // ["Barcelona","Madrid"...]
            $table->string('flag', 8)->nullable();// "🇪🇸"
            $table->boolean('popular')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_locations');
    }
};
