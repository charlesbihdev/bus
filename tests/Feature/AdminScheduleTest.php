<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\Route as BusRoute;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AdminScheduleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function test_admin_can_add_a_departure_to_a_route(): void
    {
        $route = BusRoute::factory()->create();
        $bus = Bus::factory()->create();

        $this->post(route('admin.routes.schedules.store', $route), [
            'direction' => 'forward',
            'bus_id' => $bus->id,
            'departure_time' => '07:00',
            'price' => 100, // GHS
        ])->assertRedirect();

        $this->assertDatabaseHas('schedules', ['route_id' => $route->id, 'departure_time' => '07:00', 'price' => 10000]);
    }

    public function test_adding_a_return_departure_creates_the_reverse_route(): void
    {
        $route = BusRoute::factory()->create();
        $bus = Bus::factory()->create();

        $this->post(route('admin.routes.schedules.store', $route), [
            'direction' => 'return',
            'bus_id' => $bus->id,
            'departure_time' => '18:00',
        ])->assertRedirect();

        $reverse = BusRoute::where('origin_town_id', $route->destination_town_id)
            ->where('destination_town_id', $route->origin_town_id)
            ->firstOrFail();

        $this->assertDatabaseHas('schedules', ['route_id' => $reverse->id, 'departure_time' => '18:00']);
    }

    public function test_adding_a_duplicate_time_returns_an_error(): void
    {
        $schedule = Schedule::factory()->create(['departure_time' => '07:00']);
        $bus = Bus::factory()->create();

        $this->post(route('admin.routes.schedules.store', $schedule->route), [
            'direction' => 'forward',
            'bus_id' => $bus->id,
            'departure_time' => '07:00',
        ])->assertSessionHasErrors('departure_time');

        $this->assertDatabaseCount('schedules', 1);
    }

    public function test_admin_can_toggle_and_delete_a_departure(): void
    {
        $schedule = Schedule::factory()->create(['is_active' => true]);

        $this->patch(route('admin.schedules.toggle', $schedule))->assertRedirect();
        $this->assertFalse($schedule->refresh()->is_active);

        $this->delete(route('admin.schedules.destroy', $schedule))->assertRedirect();
        $this->assertModelMissing($schedule);
    }

    public function test_admin_can_cancel_a_range_of_departures(): void
    {
        $schedule = Schedule::factory()->create();
        $from = Carbon::tomorrow()->toDateString();
        $to = Carbon::tomorrow()->addDays(2)->toDateString();

        $this->post(route('admin.schedules.departures.cancel', $schedule), compact('from', 'to'))
            ->assertRedirect();

        $this->assertDatabaseCount('departures', 3);
        $this->get(route('trips.seats', $schedule).'?date='.$from)->assertNotFound();
    }

    public function test_departures_index_renders(): void
    {
        $schedule = Schedule::factory()->create();

        $this->get(route('admin.schedules.departures.index', $schedule))
            ->assertOk()
            ->assertInertia(fn (Assert $p) => $p->component('admin/departures/index')->has('dates', 14));
    }
}
