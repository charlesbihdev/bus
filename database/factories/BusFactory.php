<?php

namespace Database\Factories;

use App\Models\Bus;
use App\Support\SeatLayout;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bus>
 */
class BusFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $layout = SeatLayout::vip45();

        return [
            'name' => 'VIP-'.fake()->unique()->numberBetween(1, 99),
            'operator' => 'BookBus VIP',
            'seat_count' => count(SeatLayout::labels($layout)),
            'layout' => $layout,
            'is_active' => true,
        ];
    }
}
