<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Bus;
use App\Models\Town;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function test_admin_can_create_a_town(): void
    {
        $this->post(route('admin.towns.store'), ['name' => 'Takoradi', 'region' => 'Western'])
            ->assertRedirect(route('admin.towns.index'));

        $this->assertDatabaseHas('towns', ['name' => 'Takoradi', 'region' => 'Western']);
    }

    public function test_admin_can_create_a_route_and_price_is_stored_in_pesewas(): void
    {
        $origin = Town::factory()->create();
        $destination = Town::factory()->create();

        $this->post(route('admin.routes.store'), [
            'origin_town_id' => $origin->id,
            'destination_town_id' => $destination->id,
            'base_price' => 120.50, // GHS
            'duration_minutes' => 300,
        ])->assertRedirect(route('admin.routes.index'));

        $this->assertDatabaseHas('routes', [
            'origin_town_id' => $origin->id,
            'destination_town_id' => $destination->id,
            'base_price' => 12050, // pesewas
        ]);
    }

    public function test_route_origin_and_destination_must_differ(): void
    {
        $town = Town::factory()->create();

        $this->post(route('admin.routes.store'), [
            'origin_town_id' => $town->id,
            'destination_town_id' => $town->id,
            'base_price' => 100,
        ])->assertSessionHasErrors('destination_town_id');
    }

    public function test_admin_can_create_a_bus_with_the_standard_layout(): void
    {
        $this->post(route('admin.buses.store'), ['name' => 'VIP-09', 'operator' => 'BookBus VIP'])
            ->assertRedirect(route('admin.buses.index'));

        $bus = Bus::where('name', 'VIP-09')->firstOrFail();
        $this->assertSame(45, $bus->seat_count);
        $this->assertCount(11, $bus->layout); // 10 rows + back row
    }

    public function test_admin_can_view_bookings_list_and_detail(): void
    {
        $booking = Booking::factory()->create();

        $this->get(route('admin.bookings.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $p) => $p->component('admin/bookings/index')->has('bookings', 1));

        $this->get(route('admin.bookings.show', $booking))
            ->assertOk()
            ->assertInertia(fn (Assert $p) => $p->component('admin/bookings/show')->where('booking.reference', $booking->reference));
    }
}
