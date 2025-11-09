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
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->index(); // e.g. 'montenegro', 'balkan', 'spain'
            $table->text('description');
            // prefer storing duration in days as int for sorting/filtering
            $table->unsignedSmallInteger('duration_days')->index(); // e.g., 10
            $table->decimal('base_price', 10, 2);
            $table->char('currency', 3)->default('EUR');
            $table->string('image_url');
            $table->json('destinations');           // ["Kotor","Budva"] or ["Barcelona","Seville"]
            $table->json('group_size');             // {"min":2,"max":10}
            $table->json('itinerary');              // day-by-day array
            $table->json('included');               // list of included services
            $table->json('not_included');           // list of excluded services
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
