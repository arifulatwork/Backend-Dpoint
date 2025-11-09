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
        Schema::create('tour_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('tour_id')->constrained('tours')->onDelete('cascade');

            // trip specifics chosen by the user
            $table->date('start_date')->nullable();
            $table->unsignedSmallInteger('travelers')->default(1);
            $table->json('customer_notes')->nullable();   // optional extras / preferences

            // pricing snapshot at booking time (don’t rely on mutable tour.base_price)
            $table->decimal('unit_price', 10, 2);         // price per traveler at time of booking
            $table->decimal('subtotal', 10, 2);           // unit_price * travelers
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);       // final to pay
            $table->char('currency', 3)->default('EUR');

            // booking lifecycle
            $table->enum('status', ['pending','confirmed','cancelled','refunded'])
                  ->default('pending')->index();

            // convenience flag (derive from payments, but handy)
            $table->boolean('paid')->default(false)->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_bookings');
    }
};
