<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Departure;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'departure_id' => Departure::factory(),
            'reference' => 'BB-'.strtoupper(Str::random(8)),
            'contact_name' => fake()->name(),
            'contact_phone' => fake()->numerify('024#######'),
            'total_amount' => 12000,
            'status' => 'pending',
            'expires_at' => now()->addMinutes(10),
            'paid_at' => null,
        ];
    }

    public function paid(): static
    {
        return $this->state(['status' => 'paid', 'expires_at' => null, 'paid_at' => now()]);
    }

    public function expired(): static
    {
        return $this->state(['status' => 'pending', 'expires_at' => now()->subMinute()]);
    }
}
