<?php

namespace Database\Factories;

use App\Models\Departure;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Departure>
 */
class DepartureFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'schedule_id' => Schedule::factory(),
            'travel_date' => Carbon::tomorrow()->toDateString(),
            'status' => 'scheduled',
            'price' => null,
        ];
    }

    public function cancelled(): static
    {
        return $this->state(['status' => 'cancelled']);
    }
}
