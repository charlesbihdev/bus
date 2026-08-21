<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddDepartureRequest;
use App\Models\Route as BusRoute;
use App\Models\Schedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class ScheduleController extends Controller
{
    /** Add a departure time to one direction of a corridor. */
    public function store(AddDepartureRequest $request, BusRoute $route): RedirectResponse
    {
        $data = $request->validated();
        $target = $data['direction'] === 'return' ? $this->reverseRoute($route) : $route;

        if ($target->schedules()->where('departure_time', $data['departure_time'])->exists()) {
            throw ValidationException::withMessages([
                'departure_time' => 'A departure at this time already exists for this direction.',
            ]);
        }

        $price = ($data['price'] ?? null) === null ? null : (int) round(((float) $data['price']) * 100);

        $target->schedules()->create([
            'bus_id' => $data['bus_id'],
            'departure_time' => $data['departure_time'],
            'price' => $price,
            'is_active' => true,
        ]);

        return back();
    }

    public function toggle(Schedule $schedule): RedirectResponse
    {
        $schedule->update(['is_active' => ! $schedule->is_active]);

        return back();
    }

    public function destroy(Schedule $schedule): RedirectResponse
    {
        $schedule->delete();

        return back();
    }

    /** Find or lazily create the reverse route, mirroring the forward settings. */
    private function reverseRoute(BusRoute $route): BusRoute
    {
        return BusRoute::firstOrCreate(
            ['origin_town_id' => $route->destination_town_id, 'destination_town_id' => $route->origin_town_id],
            ['base_price' => $route->base_price, 'duration_minutes' => $route->duration_minutes, 'is_active' => true],
        );
    }
}
