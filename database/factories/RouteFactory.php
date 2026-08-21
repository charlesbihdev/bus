<?php

namespace Database\Factories;

use App\Models\Route;
use App\Models\Town;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Route>
 */
class RouteFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'origin_town_id' => Town::factory(),
            'destination_town_id' => Town::factory(),
            'base_price' => fake()->numberBetween(5000, 20000),
            'duration_minutes' => fake()->numberBetween(60, 480),
            'is_active' => true,
        ];
    }
}
