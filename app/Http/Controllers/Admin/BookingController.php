<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Inertia\Inertia;
use Inertia\Response;

class BookingController extends Controller
{
    public function index(): Response
    {
        $bookings = Booking::query()
            ->with(['departure.schedule.route.origin', 'departure.schedule.route.destination'])
            ->withCount('seats')
            ->latest('id')
            ->limit(100)
            ->get()
            ->map(fn (Booking $b) => [
                'id' => $b->id,
                'reference' => $b->reference,
                'route' => $b->departure->schedule->route->origin->name.' → '.$b->departure->schedule->route->destination->name,
                'date' => $b->departure->travel_date->toDateString(),
                'contact_name' => $b->contact_name,
                'seats' => $b->seats_count,
                'amount' => $b->total_amount,
                'status' => $b->status,
            ]);

        return Inertia::render('admin/bookings/index', ['bookings' => $bookings]);
    }

    public function show(Booking $booking): Response
    {
        $booking->load(['seats', 'departure.schedule.route.origin', 'departure.schedule.route.destination', 'departure.schedule.bus']);
        $schedule = $booking->departure->schedule;

        return Inertia::render('admin/bookings/show', [
            'booking' => [
                'reference' => $booking->reference,
                'status' => $booking->status,
                'route' => $schedule->route->origin->name.' → '.$schedule->route->destination->name,
                'date' => $booking->departure->travel_date->toDateString(),
                'departure_time' => substr($schedule->departure_time, 0, 5),
                'bus' => $schedule->bus->name,
                'contact_name' => $booking->contact_name,
                'contact_phone' => $booking->contact_phone,
                'total_amount' => $booking->total_amount,
                'seats' => $booking->seats->map(fn ($s) => [
                    'label' => $s->seat_label,
                    'passenger_name' => $s->passenger_name,
                ]),
            ],
        ]);
    }
}
