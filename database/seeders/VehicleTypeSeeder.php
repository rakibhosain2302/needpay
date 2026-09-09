<?php

namespace Database\Seeders;

use App\Models\VehicleType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VehicleTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Bike',
                'minimum_fare' => 20.00,
                'base_fare' => 15.00,
                'per_km_rate' => 8.00,
                'per_minute_rate' => 0.50,
                'waiting_charge_per_minute' => 0.25,
                'max_passengers' => 1,
            ],
            [
                'name' => 'CNG / Auto-rickshaw',
                'minimum_fare' => 30.00,
                'base_fare' => 25.00,
                'per_km_rate' => 12.00,
                'per_minute_rate' => 0.75,
                'waiting_charge_per_minute' => 0.50,
                'max_passengers' => 3,
            ],
            [
                'name' => 'Sedan',
                'minimum_fare' => 50.00,
                'base_fare' => 40.00,
                'per_km_rate' => 18.00,
                'per_minute_rate' => 1.00,
                'waiting_charge_per_minute' => 0.75,
                'max_passengers' => 4,
            ],
            [
                'name' => 'SUV',
                'minimum_fare' => 80.00,
                'base_fare' => 60.00,
                'per_km_rate' => 25.00,
                'per_minute_rate' => 1.50,
                'waiting_charge_per_minute' => 1.00,
                'max_passengers' => 6,
            ],
        ];

        foreach ($types as $type) {
            VehicleType::updateOrCreate(
                ['slug' => Str::slug($type['name'])],
                $type + ['slug' => Str::slug($type['name']), 'status' => 'active']
            );
        }
    }
}
