<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'provider' => 'paystack',
            'reference' => (string) Str::uuid(),
            'amount' => 12000,
            'currency' => 'GHS',
            'status' => 'pending',
            'payload' => null,
        ];
    }

    public function success(): static
    {
        return $this->state(['status' => 'success']);
    }
}
