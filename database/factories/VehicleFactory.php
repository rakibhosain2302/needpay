<?php

namespace Database\Factories;

use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vehicle>
 */
class VehicleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'driver_id' => Driver::factory(),
            'vehicle_type_id' => VehicleType::factory(),
            'brand' => fake()->randomElement(['Toyota', 'Honda', 'Suzuki', 'Hyundai']),
            'model' => fake()->word(),
            'year' => fake()->numberBetween(2005, 2025),
            'color' => fake()->safeColorName(),
            'registration_number' => fake()->unique()->bothify('DHA-####'),
            'registration_expiry_date' => now()->addYears(1)->toDateString(),
            'status' => 'pending',
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'approved']);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => ['registration_expiry_date' => now()->subDay()->toDateString()]);
    }
}
