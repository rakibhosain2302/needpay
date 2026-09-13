<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_passenger_registration_works(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Jane Passenger',
            'account_type' => 'passenger',
            'email' => 'jane@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.account_type', 'passenger')
            ->assertJsonStructure(['data' => ['user', 'token']]);

        $this->assertDatabaseHas('users', ['email' => 'jane@example.com', 'account_type' => 'passenger']);
        $this->assertDatabaseMissing('drivers', ['user_id' => User::firstWhere('email', 'jane@example.com')->id]);
    }

    public function test_driver_registration_creates_driver_record(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Dan Driver',
            'account_type' => 'driver',
            'phone' => '01712345678',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertCreated();

        $user = User::firstWhere('phone', '01712345678');
        $this->assertNotNull($user);

        $this->assertDatabaseHas('drivers', [
            'user_id' => $user->id,
            'verification_status' => 'pending',
            'is_online' => false,
            'availability_status' => 'offline',
        ]);
    }

    public function test_duplicate_email_is_rejected(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Someone',
            'account_type' => 'passenger',
            'email' => 'taken@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors('email');
    }

    public function test_duplicate_phone_is_rejected(): void
    {
        User::factory()->create(['phone' => '01712345678']);

        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Someone',
            'account_type' => 'passenger',
            'phone' => '01712345678',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors('phone');
    }

    public function test_invalid_data_is_rejected(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => '',
            'account_type' => 'passenger',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors(['name', 'email', 'phone']);
    }

    public function test_password_confirmation_is_required(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Jane Passenger',
            'account_type' => 'passenger',
            'email' => 'jane2@example.com',
            'password' => 'password123',
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors('password');
    }

    public function test_public_admin_registration_is_rejected(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Sneaky Admin',
            'account_type' => 'admin',
            'email' => 'sneaky@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors('account_type');
        $this->assertDatabaseMissing('users', ['email' => 'sneaky@example.com']);
    }
}
