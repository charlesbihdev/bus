<?php

namespace App\Http\Controllers;

use App\Models\Route as BusRoute;
use App\Models\Schedule;
use App\Services\DepartureResolver;
use App\Services\SeatAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class TripController extends Controller
{
    /**
     * Browse bookable corridors. Each corridor groups a route with its reverse
     * (e.g. Kumasi→Accra and Accra→Kumasi) so the UI can offer a swap button.
     */
    public function index(): Response
    {
        $corridors = BusRoute::query()
            ->where('is_active', true)
            ->with([
                'origin', 'destination',
                'schedules' => fn ($q) => $q->where('is_active', true)->orderBy('departure_time'),
            ])
            ->get()
            ->map(fn (BusRoute $r) => [
                'route_id' => $r->id,
                'origin' => $r->origin->name,
                'destination' => $r->destination->name,
                'duration_minutes' => $r->duration_minutes,
                'times' => $r->schedules->map(fn (Schedule $s) => [
                    'schedule_id' => $s->id,
                    'departure_time' => substr($s->departure_time, 0, 5),
                    'price' => $s->price ?? $r->base_price,
                ])->values(),
                '_key' => collect([$r->origin_town_id, $r->destination_town_id])->sort()->implode('-'),
            ])
            ->filter(fn (array $d) => $d['times']->isNotEmpty())
            ->groupBy('_key')
            ->map(fn ($group) => [
                'directions' => $group->map(fn (array $d) => collect($d)->except('_key'))->values(),
            ])
            ->values();

        return Inertia::render('trips/index', ['corridors' => $corridors]);
    }

    /** Show the seat map for a schedule on a chosen date. */
    public function seats(Request $request, Schedule $schedule, DepartureResolver $resolver, SeatAvailability $availability): Response
    {
        $date = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
        ])['date'];

        $schedule->load(['route.origin', 'route.destination', 'bus']);
        $departure = $resolver->resolve($schedule, $date);

        abort_unless($resolver->isBookable($departure), 404, 'This departure is not available.');

        return Inertia::render('trips/seats', [
            'trip' => [
                'schedule_id' => $schedule->id,
                'date' => Carbon::parse($date)->toDateString(),
                'origin' => $schedule->route->origin->name,
                'destination' => $schedule->route->destination->name,
                'departure_time' => substr($schedule->departure_time, 0, 5),
                'duration_minutes' => $schedule->route->duration_minutes,
                'bus' => $schedule->bus->name,
                'price' => $departure->priceInPesewas(),
            ],
            'seatMap' => $availability->seatMap($departure),
        ]);
    }
}
