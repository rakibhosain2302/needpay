<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\VehicleType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesUsers;
use Tests\TestCase;

class VehicleTypeTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    public function test_public_users_can_view_active_vehicle_types(): void
    {
        VehicleType::factory()->create(['status' => 'active']);
        VehicleType::factory()->create(['status' => 'inactive']);

        $this->getJson('/api/v1/vehicle-types')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_admin_can_create_vehicle_type(): void
    {
        $admin = $this->adminUser(['vehicle_types.manage']);

        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/v1/admin/vehicle-types', [
            'name' => 'Premium Sedan',
            'slug' => 'premium-sedan',
            'minimum_fare' => 50,
            'base_fare' => 40,
            'per_km_rate' => 20,
            'per_minute_rate' => 2,
            'waiting_charge_per_minute' => 1,
            'max_passengers' => 4,
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('vehicle_types', ['slug' => 'premium-sedan']);
    }

    public function test_admin_can_update_vehicle_type(): void
    {
        $admin = $this->adminUser(['vehicle_types.manage']);
        $vehicleType = VehicleType::factory()->create();

        $this->actingAs($admin, 'sanctum')
            ->putJson("/api/v1/admin/vehicle-types/{$vehicleType->id}", ['base_fare' => 99.99])
            ->assertOk()
            ->assertJsonPath('data.base_fare', '99.99');
    }

    public function test_duplicate_slug_rejected(): void
    {
        $admin = $this->adminUser(['vehicle_types.manage']);
        VehicleType::factory()->create(['slug' => 'taken-slug']);

        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/v1/admin/vehicle-types', [
            'name' => 'Another Type',
            'slug' => 'taken-slug',
            'minimum_fare' => 10,
            'base_fare' => 10,
            'per_km_rate' => 10,
            'per_minute_rate' => 1,
            'waiting_charge_per_minute' => 1,
            'max_passengers' => 4,
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors('slug');
    }

    public function test_negative_fare_rejected(): void
    {
        $admin = $this->adminUser(['vehicle_types.manage']);

        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/v1/admin/vehicle-types', [
            'name' => 'Bad Type',
            'slug' => 'bad-type',
            'minimum_fare' => -10,
            'base_fare' => 10,
            'per_km_rate' => 10,
            'per_minute_rate' => 1,
            'waiting_charge_per_minute' => 1,
            'max_passengers' => 4,
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors('minimum_fare');
    }

    public function test_unauthorized_user_cannot_manage_vehicle_types(): void
    {
        $passenger = User::factory()->passenger()->create();

        $this->actingAs($passenger, 'sanctum')
            ->postJson('/api/v1/admin/vehicle-types', ['name' => 'X'])
            ->assertForbidden();
    }
}
