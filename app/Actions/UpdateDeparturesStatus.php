<?php

namespace App\Actions;

use App\Models\Schedule;
use App\Services\DepartureResolver;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;

class UpdateDeparturesStatus
{
    public function __construct(private DepartureResolver $resolver) {}

    /**
     * Set the status (scheduled|cancelled) for every date in an inclusive
     * range. Departures are materialised on demand, so a row is created for
     * any date that doesn't have one yet.
     */
    public function handle(Schedule $schedule, string $from, string $to, string $status): int
    {
        $count = 0;

        foreach (CarbonPeriod::create(Carbon::parse($from), Carbon::parse($to)) as $date) {
            $departure = $this->resolver->resolve($schedule, $date->toDateString());
            $departure->update(['status' => $status]);
            $count++;
        }

        return $count;
    }
}
