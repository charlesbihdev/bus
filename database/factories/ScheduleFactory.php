<?php

namespace Database\Factories;

use App\Models\Bus;
use App\Models\Route;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'route_id' => Route::factory(),
            'bus_id' => Bus::factory(),
            'departure_time' => fake()->randomElement(['06:00', '10:00', '14:00']),
            'is_active' => true,
        ];
    }
}
