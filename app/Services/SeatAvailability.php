<?php

namespace App\Services;

use App\Models\Departure;
use App\Support\SeatLayout;

class SeatAvailability
{
    /**
     * Seat labels that are unavailable = every seat row that currently exists
     * for the departure. Expired holds are swept before writes, so a live row
     * means the seat is either paid or actively held.
     *
     * @return array<int, string>
     */
    public function takenSeats(Departure $departure): array
    {
        return $departure->seats()->pluck('seat_label')->all();
    }

    /**
     * Render the bus layout annotated with each seat's state, ready for the UI.
     *
     * @return array{layout: array<int, array<int, array{label: string, state: string}|null>>, taken: array<int, string>}
     */
    public function seatMap(Departure $departure): array
    {
        $bus = $departure->schedule->bus;
        $taken = $this->takenSeats($departure);

        $layout = array_map(
            fn (array $row) => array_map(
                fn (?string $cell) => $cell === null ? null : [
                    'label' => $cell,
                    'state' => in_array($cell, $taken, true) ? 'booked' : 'available',
                ],
                $row,
            ),
            $bus->layout,
        );

        return ['layout' => $layout, 'taken' => $taken];
    }

    /**
     * @param  array<int, string>  $seatLabels
     * @return array<int, string> invalid labels not present in the bus layout
     */
    public function unknownSeats(Departure $departure, array $seatLabels): array
    {
        $valid = SeatLayout::labels($departure->schedule->bus->layout);

        return array_values(array_diff($seatLabels, $valid));
    }
}
