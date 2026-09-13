<?php

namespace Tests\Feature;

use App\Enums\AccountType;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_profile(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/profile')
            ->assertOk()
            ->assertJsonPath('data.id', $user->id)
            ->assertJsonMissing(['password']);
    }

    public function test_user_can_update_allowed_profile_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->putJson('/api/v1/profile', [
            'name' => 'Updated Name',
        ]);

        $response->assertOk()->assertJsonPath('data.name', 'Updated Name');
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name']);
    }

    public function test_user_cannot_change_account_type(): void
    {
        $user = User::factory()->passenger()->create();

        $this->actingAs($user, 'sanctum')->putJson('/api/v1/profile', [
            'account_type' => 'admin',
        ]);

        $this->assertSame(AccountType::Passenger, $user->fresh()->account_type);
    }

    public function test_user_cannot_change_status(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')->putJson('/api/v1/profile', [
            'status' => 'suspended',
        ]);

        $this->assertSame(UserStatus::Active, $user->fresh()->status);
    }

    public function test_user_cannot_modify_roles_through_profile_endpoint(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')->putJson('/api/v1/profile', [
            'roles' => ['super_admin'],
        ]);

        $this->assertCount(0, $user->fresh()->roles);
    }
}
