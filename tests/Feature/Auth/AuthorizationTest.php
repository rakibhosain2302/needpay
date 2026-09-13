<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_passenger_cannot_access_driver_only_endpoint(): void
    {
        $passenger = User::factory()->passenger()->create();

        $this->actingAs($passenger, 'sanctum')
            ->getJson('/api/v1/driver/ping')
            ->assertForbidden();
    }

    public function test_driver_cannot_access_admin_only_endpoint(): void
    {
        $driver = User::factory()->driver()->create();

        $this->actingAs($driver, 'sanctum')
            ->getJson('/api/v1/admin/ping')
            ->assertForbidden();
    }

    public function test_passenger_cannot_access_admin_only_endpoint(): void
    {
        $passenger = User::factory()->passenger()->create();

        $this->actingAs($passenger, 'sanctum')
            ->getJson('/api/v1/admin/ping')
            ->assertForbidden();
    }

    public function test_admin_authorization_works(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin, 'sanctum')
            ->getJson('/api/v1/admin/ping')
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    public function test_matching_account_type_can_access_its_endpoint(): void
    {
        $passenger = User::factory()->passenger()->create();

        $this->actingAs($passenger, 'sanctum')
            ->getJson('/api/v1/passenger/ping')
            ->assertOk();
    }
}
