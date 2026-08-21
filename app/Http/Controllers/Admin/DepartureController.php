<?php

namespace App\Http\Controllers\Admin;

use App\Actions\UpdateDeparturesStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\CancelDeparturesRequest;
use App\Models\Departure;
use App\Models\Schedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class DepartureController extends Controller
{
    private const WINDOW_DAYS = 14;

    public function index(Schedule $schedule): Response
    {
        $schedule->load(['route.origin', 'route.destination', 'bus']);

        $start = Carbon::today();
        $end = $start->copy()->addDays(self::WINDOW_DAYS - 1);

        $existing = $schedule->departures()
            ->whereBetween('travel_date', [$start->toDateString(), $end->toDateString()])
            ->withCount('seats')
            ->get()
            ->keyBy(fn (Departure $d) => $d->travel_date->toDateString());

        $dates = collect(range(0, self::WINDOW_DAYS - 1))->map(function (int $i) use ($start, $existing) {
            $key = $start->copy()->addDays($i)->toDateString();
            $departure = $existing->get($key);

            return [
                'date' => $key,
                'status' => $departure?->status ?? 'scheduled',
                'seats_booked' => $departure?->seats_count ?? 0,
            ];
        });

        return Inertia::render('admin/departures/index', [
            'schedule' => [
                'id' => $schedule->id,
                'origin' => $schedule->route->origin->name,
                'destination' => $schedule->route->destination->name,
                'departure_time' => substr($schedule->departure_time, 0, 5),
                'bus' => $schedule->bus->name,
            ],
            'dates' => $dates,
        ]);
    }

    public function cancel(CancelDeparturesRequest $request, Schedule $schedule, UpdateDeparturesStatus $action): RedirectResponse
    {
        $data = $request->validated();
        $action->handle($schedule, $data['from'], $data['to'], 'cancelled');

        return back();
    }

    public function reopen(CancelDeparturesRequest $request, Schedule $schedule, UpdateDeparturesStatus $action): RedirectResponse
    {
        $data = $request->validated();
        $action->handle($schedule, $data['from'], $data['to'], 'scheduled');

        return back();
    }
}
