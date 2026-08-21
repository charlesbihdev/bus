<?php

namespace Tests\Feature;

use App\Exceptions\SeatsUnavailableException;
use App\Models\Route as BusRoute;
use App\Models\Schedule;
use App\Models\User;
use App\Services\BookingService;
use App\Services\DepartureResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    private function schedule(): Schedule
    {
        return Schedule::factory()
            ->for(BusRoute::factory()->state(['base_price' => 12000]))
            ->create();
    }

    public function test_a_user_can_hold_seats_and_totals_are_correct(): void
    {
        $service = app(BookingService::class);
        $departure = app(DepartureResolver::class)->resolve($this->schedule(), Carbon::tomorrow());

        $booking = $service->hold(
            User::factory()->create(),
            $departure,
            ['1', '2'],
            'Kwame Mensah',
            '0244000000',
        );

        $this->assertSame('pending', $booking->status);
        $this->assertSame(24000, $booking->total_amount); // 2 * 120.00
        $this->assertCount(2, $booking->seats);
        $this->assertNotNull($booking->expires_at);
    }

    public function test_a_seat_cannot_be_double_booked(): void
    {
        $service = app(BookingService::class);
        $departure = app(DepartureResolver::class)->resolve($this->schedule(), Carbon::tomorrow());

        $service->hold(User::factory()->create(), $departure, ['5'], 'A', '024');

        $this->expectException(SeatsUnavailableException::class);
        $service->hold(User::factory()->create(), $departure, ['5'], 'B', '020');
    }

    public function test_expired_holds_are_released_and_seat_can_be_rebooked(): void
    {
        $service = app(BookingService::class);
        $departure = app(DepartureResolver::class)->resolve($this->schedule(), Carbon::tomorrow());

        $hold = $service->hold(User::factory()->create(), $departure, ['9'], 'A', '024');
        $hold->update(['expires_at' => Carbon::now()->subMinute()]);

        $this->assertSame(1, $service->releaseExpired($departure->fresh()));

        // Seat 9 is free again.
        $rebook = $service->hold(User::factory()->create(), $departure->fresh(), ['9'], 'B', '020');
        $this->assertSame('pending', $rebook->status);
    }
}
