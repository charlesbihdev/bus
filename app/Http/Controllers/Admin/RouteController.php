<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRouteRequest;
use App\Http\Requests\UpdateRouteRequest;
use App\Models\Bus;
use App\Models\Route as BusRoute;
use App\Models\Schedule;
use App\Models\Town;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

class RouteController extends Controller
{
    /** List corridors — each groups a route with its reverse into one row. */
    public function index(): Response
    {
        $routes = BusRoute::with(['origin', 'destination'])->withCount('schedules')->orderBy('id')->get();

        $corridors = $routes
            ->groupBy(fn (BusRoute $r) => collect([$r->origin_town_id, $r->destination_town_id])->sort()->implode('-'))
            ->map(function (Collection $group) {
                $primary = $group->first();

                return [
                    'id' => $primary->id,
                    'origin' => $primary->origin->name,
                    'destination' => $primary->destination->name,
                    'departures' => (int) $group->sum('schedules_count'),
                    'is_active' => $primary->is_active,
                ];
            })
            ->values();

        return Inertia::render('admin/routes/index', ['corridors' => $corridors]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/routes/create', ['towns' => $this->towns()]);
    }

    public function store(StoreRouteRequest $request): RedirectResponse
    {
        BusRoute::create($this->payload($request->validated()));

        return to_route('admin.routes.index');
    }

    /** The corridor editor: route settings + departures for both directions. */
    public function edit(BusRoute $route): Response
    {
        $route->load(['origin', 'destination']);
        $reverse = $this->reverseOf($route);

        return Inertia::render('admin/routes/edit', [
            'route' => [
                'id' => $route->id,
                'origin' => $route->origin->name,
                'destination' => $route->destination->name,
                'origin_town_id' => $route->origin_town_id,
                'destination_town_id' => $route->destination_town_id,
                'base_price' => $route->base_price / 100,
                'duration_minutes' => $route->duration_minutes,
                'is_active' => $route->is_active,
            ],
            'buses' => Bus::where('is_active', true)->get(['id', 'name']),
            'directions' => [
                $this->direction('forward', $route),
                $this->direction('return', $reverse, $route),
            ],
        ]);
    }

    public function update(UpdateRouteRequest $request, BusRoute $route): RedirectResponse
    {
        $route->update($this->payload($request->validated()));

        return back();
    }

    /** Toggle the whole corridor (this route and its reverse). */
    public function toggle(BusRoute $route): RedirectResponse
    {
        $active = ! $route->is_active;
        $route->update(['is_active' => $active]);
        $this->reverseOf($route)?->update(['is_active' => $active]);

        return back();
    }

    public function destroy(BusRoute $route): RedirectResponse
    {
        $this->reverseOf($route)?->delete();
        $route->delete();

        return to_route('admin.routes.index');
    }

    /**
     * Build a direction panel. For the return, the reverse route may not exist
     * yet — we still describe the towns so the UI can offer to add times.
     *
     * @return array<string, mixed>
     */
    private function direction(string $key, ?BusRoute $route, ?BusRoute $mirror = null): array
    {
        // When the reverse route doesn't exist yet, describe it from the mirror (swapped).
        $origin = $route ? $route->origin->name : $mirror->destination->name;
        $destination = $route ? $route->destination->name : $mirror->origin->name;

        return [
            'key' => $key,
            'origin' => $origin,
            'destination' => $destination,
            'departures' => $route
                ? $route->schedules()->with('bus')->orderBy('departure_time')->get()->map(fn (Schedule $s) => [
                    'id' => $s->id,
                    'departure_time' => substr($s->departure_time, 0, 5),
                    'price' => $s->price ?? $route->base_price, // pesewas; cedis() formats it
                    'custom_price' => $s->price !== null,
                    'bus' => $s->bus->name,
                    'is_active' => $s->is_active,
                ])->values()
                : [],
        ];
    }

    private function reverseOf(BusRoute $route): ?BusRoute
    {
        return BusRoute::where('origin_town_id', $route->destination_town_id)
            ->where('destination_town_id', $route->origin_town_id)
            ->first();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function payload(array $data): array
    {
        $data['base_price'] = (int) round(((float) $data['base_price']) * 100);

        return $data;
    }

    /** @return Collection<int, array{id: int, label: string}> */
    private function towns(): Collection
    {
        return Town::where('is_active', true)->orderBy('name')->get()
            ->map(fn (Town $t) => ['id' => $t->id, 'label' => $t->name]);
    }
}
