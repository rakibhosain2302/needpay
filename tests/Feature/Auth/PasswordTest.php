<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_current_password_is_required(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/auth/change-password', [
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors('current_password');
    }

    public function test_incorrect_current_password_is_rejected(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password123')]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/auth/change-password', [
            'current_password' => 'wrong-password',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors('current_password');
    }

    public function test_password_change_works_and_old_password_stops_working(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password123')]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/auth/change-password', [
                'current_password' => 'password123',
                'new_password' => 'newpassword123',
                'new_password_confirmation' => 'newpassword123',
            ]);

        $response->assertOk();

        $loginWithOld = $this->postJson('/api/v1/auth/login', [
            'login_type' => 'email',
            'identifier' => $user->email,
            'password' => 'password123',
        ]);
        $loginWithOld->assertUnprocessable();

        $loginWithNew = $this->postJson('/api/v1/auth/login', [
            'login_type' => 'email',
            'identifier' => $user->email,
            'password' => 'newpassword123',
        ]);
        $loginWithNew->assertOk();
    }
}
