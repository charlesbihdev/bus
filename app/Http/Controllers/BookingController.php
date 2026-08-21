<?php

namespace App\Http\Controllers;

use App\Exceptions\SeatsUnavailableException;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Schedule;
use App\Services\BookingService;
use App\Services\DepartureResolver;
use App\Services\SeatAvailability;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BookingController extends Controller
{
    /** Create a pending hold for the selected seats, then go to checkout. */
    public function store(
        StoreBookingRequest $request,
        DepartureResolver $resolver,
        SeatAvailability $availability,
        BookingService $bookings,
    ): RedirectResponse {
        $data = $request->validated();

        $schedule = Schedule::findOrFail($data['schedule_id']);
        $departure = $resolver->resolve($schedule, $data['date']);
        abort_unless($resolver->isBookable($departure), 404);

        $unknown = $availability->unknownSeats($departure, $data['seats']);
        if ($unknown !== []) {
            return back()->withErrors(['seats' => 'Unknown seat(s): ' . implode(', ', $unknown)]);
        }

        try {
            $booking = $bookings->hold(
                $departure,
                $data['seats'],
                $data['contact_name'],
                $data['contact_phone'],
                $data['passenger_names'] ?? [],
            );
        } catch (SeatsUnavailableException $e) {
            return back()->withErrors(['seats' => 'Sorry, seat(s) ' . implode(', ', $e->seats) . ' were just taken.']);
        }

        return to_route('bookings.show', $booking);
    }

    /** Checkout summary for a pending/paid booking. */
    public function show(Request $request, Booking $booking): Response
    {
        // abort_unless($booking->user_id === $request->user()->id, 403);

        $booking->load(['seats', 'departure.schedule.route.origin', 'departure.schedule.route.destination']);
        $route = $booking->departure->schedule->route;

        return Inertia::render('bookings/checkout', [
            'booking' => [
                'reference' => $booking->reference,
                'status' => $booking->status,
                'origin' => $route->origin->name,
                'destination' => $route->destination->name,
                'date' => $booking->departure->travel_date->toDateString(),
                'departure_time' => substr($booking->departure->schedule->departure_time, 0, 5),
                'seats' => $booking->seats->pluck('seat_label'),
                'contact_name' => $booking->contact_name,
                'contact_phone' => $booking->contact_phone,
                'total_amount' => $booking->total_amount,
                'expires_at' => $booking->expires_at?->toIso8601String(),
            ],
        ]);
    }
}
