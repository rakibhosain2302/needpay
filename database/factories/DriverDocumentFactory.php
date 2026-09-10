<?php

namespace Database\Factories;

use App\Models\Driver;
use App\Models\DriverDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DriverDocument>
 */
class DriverDocumentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'driver_id' => Driver::factory(),
            'document_type' => 'nid',
            'document_number' => fake()->unique()->bothify('NID-########'),
            'file_path' => 'driver-documents/placeholder.pdf',
            'status' => 'pending',
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'verified_at' => now(),
        ]);
    }
}
