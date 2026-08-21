<?php

namespace Tests\Feature;

use App\Models\Route as BusRoute;
use App\Models\Schedule;
use App\Models\Town;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class BookingHttpTest extends TestCase
{
    use RefreshDatabase;

    private function schedule(): Schedule
    {
        $route = BusRoute::factory()
            ->for(Town::factory()->state(['name' => 'Kumasi']), 'origin')
            ->for(Town::factory()->state(['name' => 'Accra']), 'destination')
            ->state(['base_price' => 12000, 'duration_minutes' => 300])
            ->create();

        return Schedule::factory()->for($route)->create();
    }

    public function test_guests_can_browse_the_public_landing_and_seat_map(): void
    {
        $schedule = $this->schedule();
        $date = Carbon::tomorrow()->toDateString();

        $this->get(route('home'))
            ->assertOk()
            ->assertInertia(fn (Assert $p) => $p->component('trips/index')->has('corridors', 1));

        $this->get(route('trips.seats', $schedule).'?date='.$date)
            ->assertOk()
            ->assertInertia(fn (Assert $p) => $p->component('trips/seats')->where('trip.origin', 'Kumasi'));
    }

    public function test_reverse_route_is_grouped_into_one_corridor_with_two_directions(): void
    {
        $kumasi = Town::factory()->create(['name' => 'Kumasi']);
        $accra = Town::factory()->create(['name' => 'Accra']);

        $forward = BusRoute::factory()->for($kumasi, 'origin')->for($accra, 'destination')->create();
        $reverse = BusRoute::factory()->for($accra, 'origin')->for($kumasi, 'destination')->create();
        Schedule::factory()->for($forward)->create();
        Schedule::factory()->for($reverse)->create();

        $this->get(route('home'))
            ->assertOk()
            ->assertInertia(fn (Assert $p) => $p
                ->component('trips/index')
                ->has('corridors', 1)
                ->has('corridors.0.directions', 2),
            );
    }

    public function test_full_booking_flow_renders_and_persists(): void
    {
        $this->actingAs(User::factory()->create());
        $schedule = $this->schedule();
        $date = Carbon::tomorrow()->toDateString();

        $this->get(route('home'))
            ->assertOk()
            ->assertInertia(fn (Assert $p) => $p->component('trips/index')->has('corridors', 1));

        $this->get(route('trips.seats', $schedule).'?date='.$date)
            ->assertOk()
            ->assertInertia(fn (Assert $p) => $p->component('trips/seats')->where('trip.origin', 'Kumasi')->has('seatMap.layout'));

        $res = $this->post(route('bookings.store'), [
            'schedule_id' => $schedule->id,
            'date' => $date,
            'seats' => ['1', '2'],
            'contact_name' => 'Kwame',
            'contact_phone' => '0244000000',
        ]);

        $res->assertRedirect();
        $this->assertDatabaseHas('bookings', ['contact_name' => 'Kwame', 'total_amount' => 24000, 'status' => 'pending']);
        $this->assertDatabaseCount('booking_seats', 2);

        $this->get($res->headers->get('Location'))
            ->assertOk()
            ->assertInertia(fn (Assert $p) => $p->component('bookings/checkout')->where('booking.seats', ['1', '2']));
    }
}
