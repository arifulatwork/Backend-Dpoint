<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('country', 100);
            $table->string('city', 100);
            $table->string('image')->nullable();
            $table->json('coordinates')->nullable()->comment('{ "lat": number, "lng": number }');
            $table->enum('visit_type', ['individual', 'group', 'company'])->default('individual');
            $table->json('highlights')->nullable();
            $table->json('cuisine')->nullable();
            $table->text('description')->nullable();
            $table->decimal('max_price', 10, 2)->nullable();
            $table->timestamps();

            // ADD generated columns for latitude and longitude
            $table->double('latitude')->virtualAs('JSON_UNQUOTE(JSON_EXTRACT(coordinates, "$.lat"))');
            $table->double('longitude')->virtualAs('JSON_UNQUOTE(JSON_EXTRACT(coordinates, "$.lng"))');

            // Now index these generated columns
            $table->index('latitude');
            $table->index('longitude');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
