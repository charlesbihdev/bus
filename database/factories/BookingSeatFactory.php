<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Departure;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BookingSeat>
 */
class BookingSeatFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'departure_id' => Departure::factory(),
            'seat_label' => (string) fake()->unique()->numberBetween(1, 45),
            'passenger_name' => null,
        ];
    }
}
