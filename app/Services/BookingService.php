<?php

namespace App\Services;

use App\Exceptions\SeatsUnavailableException;
use App\Models\Booking;
use App\Models\Departure;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingService
{
    /** How long a pending, unpaid booking holds its seats. */
    public const HOLD_MINUTES = 10;

    public function __construct(private SeatAvailability $availability) {}

    /**
     * Place a hold: create a pending booking + seats in one transaction.
     * The unique(departure_id, seat_label) index is the final race guard.
     *
     * @param  array<int, string>  $seatLabels
     * @param  array<string, string>  $passengerNames  keyed by seat label
     *
     * @throws SeatsUnavailableException
     */
    public function hold(
        Departure $departure,
        array $seatLabels,
        string $contactName,
        string $contactPhone,
        array $passengerNames = [],
    ): Booking {
        $this->releaseExpired($departure);

        $unit = $departure->priceInPesewas();

        return DB::transaction(function () use (
            $departure,
            $seatLabels,
            $contactName,
            $contactPhone,
            $passengerNames,
            $unit
        ) {
            $clash = array_intersect($seatLabels, $this->availability->takenSeats($departure));
            if ($clash !== []) {
                throw new SeatsUnavailableException(array_values($clash));
            }

            $booking = $departure->bookings()->create([
                'reference' => 'BB-' . strtoupper(Str::random(8)),
                'contact_name' => $contactName,
                'contact_phone' => $contactPhone,
                'total_amount' => $unit * count($seatLabels),
                'status' => 'pending',
                'expires_at' => Carbon::now()->addMinutes(self::HOLD_MINUTES),
            ]);

            try {
                foreach ($seatLabels as $label) {
                    $booking->seats()->create([
                        'departure_id' => $departure->id,
                        'seat_label' => $label,
                        'passenger_name' => $passengerNames[$label] ?? null,
                    ]);
                }
            } catch (QueryException) {
                throw new SeatsUnavailableException($seatLabels);
            }

            return $booking;
        });
    }

    /** Confirm a paid booking (called after payment succeeds). */
    public function markPaid(Booking $booking): void
    {
        $booking->update([
            'status' => 'paid',
            'expires_at' => null,
            'paid_at' => Carbon::now(),
        ]);
    }

    /** Delete expired pending holds so their seats free up for re-selection. */
    public function releaseExpired(?Departure $departure = null): int
    {
        $query = Booking::query()
            ->where('status', 'pending')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', Carbon::now());

        if ($departure) {
            $query->where('departure_id', $departure->id);
        }

        // Cascade delete removes the booking_seats rows (freeing the seats).
        return $query->get()->each->delete()->count();
    }
}
