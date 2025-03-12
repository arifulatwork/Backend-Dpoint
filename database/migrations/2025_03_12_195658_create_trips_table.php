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
            $table->string('title');
            $table->text('description');
            $table->string('type');
            $table->string('duration');
            $table->decimal('price', 8, 2);
            $table->decimal('original_price', 8, 2);
            $table->integer('discount_percentage');
            $table->string('image');
            $table->string('start_time');
            $table->string('end_time');
            $table->json('highlights');
            $table->json('included');
            $table->string('meeting_point');
            $table->integer('max_participants');
            $table->json('special_offer')->nullable();
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
