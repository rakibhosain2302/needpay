<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_with_email_works(): void
    {
        User::factory()->create([
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'login_type' => 'email',
            'identifier' => 'jane@example.com',
            'password' => 'password123',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['data' => ['user', 'token']]);
    }

    public function test_login_with_phone_works(): void
    {
        User::factory()->create([
            'phone' => '01712345678',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'login_type' => 'phone',
            'identifier' => '01712345678',
            'password' => 'password123',
        ]);

        $response->assertOk()->assertJsonPath('success', true);
    }

    public function test_invalid_credentials_are_rejected(): void
    {
        User::factory()->create([
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'login_type' => 'email',
            'identifier' => 'jane@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors('identifier');
    }

    public function test_unknown_identifier_gives_generic_error(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'login_type' => 'email',
            'identifier' => 'nobody@example.com',
            'password' => 'password123',
        ]);

        $response->assertUnprocessable();
        $message = $response->json('errors.identifier.0');
        $this->assertStringNotContainsString('exist', strtolower($message));
    }

    public function test_suspended_user_cannot_login(): void
    {
        User::factory()->suspended()->create([
            'email' => 'suspended@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'login_type' => 'email',
            'identifier' => 'suspended@example.com',
            'password' => 'password123',
        ]);

        $response->assertUnprocessable();
    }

    public function test_banned_user_cannot_login(): void
    {
        User::factory()->banned()->create([
            'email' => 'banned@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'login_type' => 'email',
            'identifier' => 'banned@example.com',
            'password' => 'password123',
        ]);

        $response->assertUnprocessable();
    }
}
