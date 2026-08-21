<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\Town;
use App\Support\SeatLayout;
use Illuminate\Database\Seeder;

class BusServiceSeeder extends Seeder
{
    public function run(): void
    {
        $kumasi = Town::firstOrCreate(['name' => 'Kumasi'], ['region' => 'Ashanti']);
        $accra = Town::firstOrCreate(['name' => 'Accra'], ['region' => 'Greater Accra']);

        $route = Route::firstOrCreate(
            ['origin_town_id' => $kumasi->id, 'destination_town_id' => $accra->id],
            ['base_price' => 12000, 'duration_minutes' => 300], // GHS 120.00, ~5h
        );

        $layout = SeatLayout::vip45();
        $bus = Bus::firstOrCreate(
            ['name' => 'VIP-01'],
            [
                'operator' => 'BookBus VIP',
                'seat_count' => count(SeatLayout::labels($layout)),
                'layout' => $layout,
            ],
        );

        // Preset daily departure times riders choose from.
        foreach (['06:00', '10:00', '14:00'] as $time) {
            Schedule::firstOrCreate(
                ['route_id' => $route->id, 'departure_time' => $time],
                ['bus_id' => $bus->id],
            );
        }

        // Return direction (Accra -> Kumasi) so the swap button is live.
        $return = Route::firstOrCreate(
            ['origin_town_id' => $accra->id, 'destination_town_id' => $kumasi->id],
            ['base_price' => 12000, 'duration_minutes' => 300],
        );

        foreach (['08:00', '13:00', '17:00'] as $time) {
            Schedule::firstOrCreate(
                ['route_id' => $return->id, 'departure_time' => $time],
                ['bus_id' => $bus->id],
            );
        }
    }
}
