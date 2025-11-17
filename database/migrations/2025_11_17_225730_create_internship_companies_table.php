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
        Schema::create('internship_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo_url')->nullable();
            $table->string('location');                // "Barcelona, Spain" / "Remote"
            $table->string('field_slug');              // matches internship_fields.slug or FE field ids
            $table->decimal('rating', 2, 1)->default(0);
            $table->unsignedInteger('reviews')->default(0);
            $table->enum('work_mode', ['online', 'offline', 'hybrid'])->default('online');
            $table->string('duration');                // "4-6 months"
            $table->string('hours');                   // "20-30h/week"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_companies');
    }
};
