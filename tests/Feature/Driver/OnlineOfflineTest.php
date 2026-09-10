<?php

namespace Tests\Feature\Driver;

use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesUsers;
use Tests\TestCase;

class OnlineOfflineTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    private function makeReadyDriver(): Driver
    {
        $driver = $this->driverUser(['verification_status' => 'approved']);
        Vehicle::factory()->for($driver)->approved()->create();

        return $driver;
    }

    public function test_approved_driver_with_vehicle_can_go_online(): void
    {
        $driver = $this->makeReadyDriver();

        $this->actingAs($driver->user, 'sanctum')
            ->postJson('/api/v1/driver/online')
            ->assertOk()
            ->assertJsonPath('data.availability_status', 'online')
            ->assertJsonPath('data.is_online', true);
    }

    public function test_unapproved_driver_cannot_go_online(): void
    {
        $driver = $this->driverUser();
        Vehicle::factory()->for($driver)->approved()->create();

        $this->actingAs($driver->user, 'sanctum')
            ->postJson('/api/v1/driver/online')
            ->assertUnprocessable();
    }

    public function test_driver_without_approved_vehicle_cannot_go_online(): void
    {
        $driver = $this->driverUser(['verification_status' => 'approved']);

        $this->actingAs($driver->user, 'sanctum')
            ->postJson('/api/v1/driver/online')
            ->assertUnprocessable();
    }

    public function test_suspended_driver_cannot_go_online(): void
    {
        $driver = $this->driverUser(['verification_status' => 'suspended']);
        Vehicle::factory()->for($driver)->approved()->create();

        $this->actingAs($driver->user, 'sanctum')
            ->postJson('/api/v1/driver/online')
            ->assertUnprocessable();
    }

    public function test_driver_can_go_offline(): void
    {
        $driver = $this->makeReadyDriver();
        $driver->forceFill(['is_online' => true, 'availability_status' => 'online'])->save();

        $this->actingAs($driver->user, 'sanctum')
            ->postJson('/api/v1/driver/offline')
            ->assertOk()
            ->assertJsonPath('data.availability_status', 'offline');
    }

    public function test_busy_driver_cannot_arbitrarily_go_offline(): void
    {
        $driver = $this->makeReadyDriver();
        $driver->forceFill(['is_online' => true, 'availability_status' => 'busy'])->save();

        $this->actingAs($driver->user, 'sanctum')
            ->postJson('/api/v1/driver/offline')
            ->assertUnprocessable();
    }

    public function test_on_trip_driver_cannot_arbitrarily_go_offline(): void
    {
        $driver = $this->makeReadyDriver();
        $driver->forceFill(['is_online' => true, 'availability_status' => 'on_trip'])->save();

        $this->actingAs($driver->user, 'sanctum')
            ->postJson('/api/v1/driver/offline')
            ->assertUnprocessable();
    }
}
