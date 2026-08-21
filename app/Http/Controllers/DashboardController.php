<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Schedule;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        $recent = Booking::query()
            ->with(['departure.schedule.route.origin', 'departure.schedule.route.destination'])
            ->withCount('seats')
            ->latest('id')
            ->limit(6)
            ->get()
            ->map(fn (Booking $b) => [
                'reference' => $b->reference,
                'route' => $b->departure->schedule->route->origin->name.' → '.$b->departure->schedule->route->destination->name,
                'date' => $b->departure->travel_date->toDateString(),
                'seats' => $b->seats_count,
                'amount' => $b->total_amount,
                'status' => $b->status,
            ]);

        return Inertia::render('dashboard', [
            'stats' => [
                'active_trips' => Schedule::where('is_active', true)->count(),
                'total_trips' => Schedule::count(),
                'paid_bookings' => Booking::where('status', 'paid')->count(),
                'pending_holds' => Booking::where('status', 'pending')->count(),
                'seats_sold' => BookingSeat::whereHas('booking', fn ($q) => $q->where('status', 'paid'))->count(),
                'revenue' => (int) Booking::where('status', 'paid')->sum('total_amount'),
            ],
            'recent' => $recent,
        ]);
    }
}
