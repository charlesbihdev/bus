<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('departure_id')->constrained('departures')->cascadeOnDelete();
            $table->string('seat_label'); // e.g. "12"
            $table->string('passenger_name')->nullable();
            $table->timestamps();

            // Hard guarantee: one seat can only be held/sold once per departure.
            $table->unique(['departure_id', 'seat_label']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_seats');
    }
};
