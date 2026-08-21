<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('departure_id')->constrained('departures')->cascadeOnDelete();
            $table->string('reference')->unique();
            $table->string('contact_name');
            $table->string('contact_phone');
            $table->unsignedInteger('total_amount'); // pesewas
            $table->string('status')->default('pending'); // pending | paid | cancelled | expired
            $table->timestamp('expires_at')->nullable(); // hold window for pending bookings
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['departure_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
