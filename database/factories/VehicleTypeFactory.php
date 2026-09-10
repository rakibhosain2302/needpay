<?php

namespace Database\Factories;

use App\Models\VehicleType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<VehicleType>
 */
class VehicleTypeFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->randomElement(['Bike', 'Sedan', 'SUV', 'Van', 'Auto']).' '.fake()->unique()->numberBetween(1, 100000);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'minimum_fare' => 30,
            'base_fare' => 20,
            'per_km_rate' => 10,
            'per_minute_rate' => 1,
            'waiting_charge_per_minute' => 0.5,
            'max_passengers' => 4,
            'status' => 'active',
        ];
    }
}
