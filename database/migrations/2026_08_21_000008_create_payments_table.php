<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->string('provider')->default('paystack');
            $table->string('reference')->unique(); // idempotency key for webhook/callback
            $table->unsignedInteger('amount'); // pesewas
            $table->string('currency', 3)->default('GHS');
            $table->string('status')->default('pending'); // pending | success | failed
            $table->json('payload')->nullable(); // raw gateway response
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
