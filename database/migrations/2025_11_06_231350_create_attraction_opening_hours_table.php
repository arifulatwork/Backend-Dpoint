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
        Schema::create('attraction_opening_hours', function (Blueprint $table) {
            $table->id();
             $table->foreignId('attraction_id')->constrained()->cascadeOnDelete();

            // 0=Sunday ... 6=Saturday (matches PHP Carbon::dayOfWeek)
            $table->unsignedTinyInteger('day_of_week'); 

            // nullable if closed that day
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();

            $table->boolean('is_closed')->default(false);

            // optional: store tz if attractions can have different timezones
            $table->string('timezone', 64)->nullable();

            $table->timestamps();

            $table->unique(['attraction_id', 'day_of_week']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attraction_opening_hours');
    }
};
