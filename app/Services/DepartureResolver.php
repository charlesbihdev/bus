<?php

namespace App\Services;

use App\Models\Departure;
use App\Models\Schedule;
use Illuminate\Support\Carbon;

class DepartureResolver
{
    /**
     * Get (or lazily create) the departure for a schedule on a given date.
     * Schedules run every day, so a row is materialised on demand; admins
     * only create a row up-front when they want to cancel a specific date.
     */
    public function resolve(Schedule $schedule, string|Carbon $date): Departure
    {
        $travelDate = Carbon::parse($date)->toDateString();

        // whereDate compares the date part only; the column stores a datetime,
        // so an exact-string firstOrCreate would miss and insert a duplicate.
        $departure = Departure::query()
            ->where('schedule_id', $schedule->id)
            ->whereDate('travel_date', $travelDate)
            ->first();

        return $departure ?? Departure::create([
            'schedule_id' => $schedule->id,
            'travel_date' => $travelDate,
            'status' => 'scheduled',
        ]);
    }

    public function isBookable(Departure $departure): bool
    {
        return $departure->status === 'scheduled'
            && $departure->travel_date->startOfDay()->isFuture()
            && $departure->schedule->is_active;
    }
}
