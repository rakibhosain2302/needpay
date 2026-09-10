<?php

namespace Tests\Feature\Driver;

use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesUsers;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    public function test_driver_can_create_vehicle(): void
    {
        $driver = $this->driverUser();
        $vehicleType = VehicleType::factory()->create();

        $response = $this->actingAs($driver->user, 'sanctum')->postJson('/api/v1/driver/vehicles', [
            'vehicle_type_id' => $vehicleType->id,
            'brand' => 'Toyota',
            'model' => 'Axio',
            'year' => 2020,
            'registration_number' => 'DHA-1234',
        ]);

        $response->assertCreated()->assertJsonPath('data.status', 'pending');
        $this->assertDatabaseHas('vehicles', ['driver_id' => $driver->id, 'registration_number' => 'DHA-1234']);
    }

    public function test_driver_can_view_own_vehicles(): void
    {
        $driver = $this->driverUser();
        Vehicle::factory()->for($driver)->create();

        $this->actingAs($driver->user, 'sanctum')
            ->getJson('/api/v1/driver/vehicles')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_driver_cannot_access_another_drivers_vehicle(): void
    {
        $driverA = $this->driverUser();
        $driverB = $this->driverUser();
        $vehicle = Vehicle::factory()->for($driverB)->create();

        $this->actingAs($driverA->user, 'sanctum')
            ->getJson("/api/v1/driver/vehicles/{$vehicle->id}")
            ->assertForbidden();
    }

    public function test_duplicate_registration_number_rejected(): void
    {
        $driver = $this->driverUser();
        $vehicleType = VehicleType::factory()->create();
        Vehicle::factory()->create(['registration_number' => 'DHA-9999']);

        $response = $this->actingAs($driver->user, 'sanctum')->postJson('/api/v1/driver/vehicles', [
            'vehicle_type_id' => $vehicleType->id,
            'brand' => 'Honda',
            'model' => 'Civic',
            'year' => 2021,
            'registration_number' => 'DHA-9999',
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors('registration_number');
    }

    public function test_invalid_vehicle_type_rejected(): void
    {
        $driver = $this->driverUser();

        $response = $this->actingAs($driver->user, 'sanctum')->postJson('/api/v1/driver/vehicles', [
            'vehicle_type_id' => 99999,
            'brand' => 'Honda',
            'model' => 'Civic',
            'year' => 2021,
            'registration_number' => 'DHA-5555',
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors('vehicle_type_id');
    }

    public function test_admin_can_approve_vehicle(): void
    {
        $driver = $this->driverUser();
        $vehicle = Vehicle::factory()->for($driver)->create();
        $admin = $this->adminUser(['vehicles.verify']);

        $this->actingAs($admin, 'sanctum')
            ->postJson("/api/v1/admin/vehicles/{$vehicle->id}/approve")
            ->assertOk()
            ->assertJsonPath('data.status', 'approved');
    }

    public function test_admin_can_reject_vehicle(): void
    {
        $driver = $this->driverUser();
        $vehicle = Vehicle::factory()->for($driver)->create();
        $admin = $this->adminUser(['vehicles.verify']);

        $this->actingAs($admin, 'sanctum')
            ->postJson("/api/v1/admin/vehicles/{$vehicle->id}/reject", ['rejection_reason' => 'Bad photo'])
            ->assertOk()
            ->assertJsonPath('data.status', 'rejected');
    }

    public function test_admin_can_suspend_vehicle(): void
    {
        $driver = $this->driverUser();
        $vehicle = Vehicle::factory()->for($driver)->approved()->create();
        $admin = $this->adminUser(['vehicles.verify']);

        $this->actingAs($admin, 'sanctum')
            ->postJson("/api/v1/admin/vehicles/{$vehicle->id}/suspend")
            ->assertOk()
            ->assertJsonPath('data.status', 'suspended');
    }
}
