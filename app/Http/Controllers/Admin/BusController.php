<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBusRequest;
use App\Http\Requests\UpdateBusRequest;
use App\Models\Bus;
use App\Support\SeatLayout;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class BusController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/buses/index', [
            'buses' => Bus::orderBy('name')->get(['id', 'name', 'operator', 'seat_count', 'is_active']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/buses/create');
    }

    public function store(StoreBusRequest $request): RedirectResponse
    {
        $layout = SeatLayout::vip45();

        Bus::create([
            ...$request->validated(),
            'seat_count' => count(SeatLayout::labels($layout)),
            'layout' => $layout,
        ]);

        return to_route('admin.buses.index');
    }

    public function edit(Bus $bus): Response
    {
        return Inertia::render('admin/buses/edit', [
            'bus' => $bus->only('id', 'name', 'operator', 'is_active'),
        ]);
    }

    public function update(UpdateBusRequest $request, Bus $bus): RedirectResponse
    {
        $bus->update($request->validated());

        return to_route('admin.buses.index');
    }

    public function destroy(Bus $bus): RedirectResponse
    {
        $bus->delete();

        return to_route('admin.buses.index');
    }

    public function toggle(Bus $bus): RedirectResponse
    {
        $bus->update(['is_active' => ! $bus->is_active]);

        return back();
    }
}
