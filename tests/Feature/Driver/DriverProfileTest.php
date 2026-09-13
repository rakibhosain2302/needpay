<?php

namespace Tests\Feature\Driver;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesUsers;
use Tests\TestCase;

class DriverProfileTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    public function test_driver_can_view_own_profile(): void
    {
        $driver = $this->driverUser();

        $this->actingAs($driver->user, 'sanctum')
            ->getJson('/api/v1/driver/profile')
            ->assertOk()
            ->assertJsonPath('data.id', $driver->id);
    }

    public function test_driver_can_update_own_profile(): void
    {
        $driver = $this->driverUser();

        $this->actingAs($driver->user, 'sanctum')
            ->putJson('/api/v1/driver/profile', ['address' => 'New Address 123'])
            ->assertOk()
            ->assertJsonPath('data.address', 'New Address 123');

        $this->assertDatabaseHas('drivers', ['id' => $driver->id, 'address' => 'New Address 123']);
    }

    public function test_passenger_cannot_access_driver_profile(): void
    {
        $passenger = User::factory()->passenger()->create();

        $this->actingAs($passenger, 'sanctum')
            ->getJson('/api/v1/driver/profile')
            ->assertForbidden();
    }

    public function test_driver_cannot_modify_verification_status_via_profile(): void
    {
        $driver = $this->driverUser();

        $this->actingAs($driver->user, 'sanctum')->putJson('/api/v1/driver/profile', [
            'verification_status' => 'approved',
        ]);

        $this->assertSame('pending', $driver->fresh()->verification_status->value);
    }
}
