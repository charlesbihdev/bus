<?php

namespace App\Console\Commands;

use App\Services\BookingService;
use Illuminate\Console\Command;

class ReleaseExpiredHolds extends Command
{
    protected $signature = 'bookings:release-expired';

    protected $description = 'Delete expired pending seat holds so the seats can be booked again';

    public function handle(BookingService $bookings): int
    {
        $released = $bookings->releaseExpired();

        $this->info("Released {$released} expired hold(s).");

        return self::SUCCESS;
    }
}
