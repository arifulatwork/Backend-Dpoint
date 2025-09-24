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
        Schema::create('internship_categories', function (Blueprint $table) {
            $table->id();
             $table->string('slug', 64)->unique();   // e.g. it, management, marketing
            $table->string('name', 120)->index();   // e.g. Information Technology
            $table->string('icon', 100)->nullable();// optional (lucide icon name)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_categories');
    }
};
