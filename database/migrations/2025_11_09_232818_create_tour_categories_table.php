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
        Schema::create('tour_categories', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // 'montenegro', 'balkan', 'spain'
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable(); // e.g., images/montenegro-trips.jpg
            $table->unsignedSmallInteger('duration_min')->nullable();
            $table->unsignedSmallInteger('duration_max')->nullable();
            $table->decimal('price_min', 10, 2)->nullable();
            $table->decimal('price_max', 10, 2)->nullable();
            $table->json('destinations')->nullable(); // ["Kotor","Budva"]
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_categories');
    }
};
