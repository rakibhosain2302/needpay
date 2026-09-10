<?php

namespace Database\Factories;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Driver>
 */
class DriverFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->driver(),
            'date_of_birth' => fake()->date(),
            'gender' => fake()->randomElement(['male', 'female', 'other']),
            'address' => fake()->address(),
            'license_number' => fake()->unique()->bothify('DL-########'),
            'license_expiry_date' => now()->addYears(2)->toDateString(),
            'verification_status' => 'pending',
            'is_online' => false,
            'availability_status' => 'offline',
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => ['verification_status' => 'approved']);
    }

    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => ['verification_status' => 'suspended']);
    }
}
