<?php

namespace App\Services;

use App\Enums\VehicleStatus;
use App\Models\Driver;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class VehicleService
{
    public function __construct(private readonly AuditLogService $auditLog) {}

    public function create(Driver $driver, array $data, ?UploadedFile $photo): Vehicle
    {
        return DB::transaction(function () use ($driver, $data, $photo) {
            return Vehicle::create([
                'driver_id' => $driver->id,
                'vehicle_type_id' => $data['vehicle_type_id'],
                'brand' => $data['brand'],
                'model' => $data['model'],
                'year' => $data['year'],
                'color' => $data['color'] ?? null,
                'registration_number' => $data['registration_number'],
                'registration_expiry_date' => $data['registration_expiry_date'] ?? null,
                'photo' => $photo ? $photo->store('vehicle-photos', 'public') : null,
                'status' => VehicleStatus::Pending,
            ]);
        });
    }

    public function update(Vehicle $vehicle, array $data, ?UploadedFile $photo): Vehicle
    {
        return DB::transaction(function () use ($vehicle, $data, $photo) {
            $updates = array_intersect_key($data, array_flip([
                'vehicle_type_id', 'brand', 'model', 'year', 'color',
                'registration_number', 'registration_expiry_date',
            ]));

            if ($photo) {
                $updates['photo'] = $photo->store('vehicle-photos', 'public');
            }

            // A material change resets approval — the admin should re-review the updated details.
            if (! empty($updates) && $vehicle->status === VehicleStatus::Approved) {
                $updates['status'] = VehicleStatus::Pending;
            }

            $vehicle->update($updates);

            return $vehicle->fresh();
        });
    }

    public function approve(Vehicle $vehicle, User $admin): Vehicle
    {
        return DB::transaction(function () use ($vehicle, $admin) {
            $old = $vehicle->only(['status']);

            $vehicle->forceFill(['status' => VehicleStatus::Approved])->save();

            $this->auditLog->log($admin, 'vehicle.approved', $vehicle, $old, ['status' => VehicleStatus::Approved->value]);

            return $vehicle;
        });
    }

    public function reject(Vehicle $vehicle, User $admin, string $reason): Vehicle
    {
        return DB::transaction(function () use ($vehicle, $admin, $reason) {
            $old = $vehicle->only(['status']);

            $vehicle->forceFill(['status' => VehicleStatus::Rejected])->save();

            $this->auditLog->log($admin, 'vehicle.rejected', $vehicle, $old, [
                'status' => VehicleStatus::Rejected->value,
                'rejection_reason' => $reason,
            ]);

            return $vehicle;
        });
    }

    public function suspend(Vehicle $vehicle, User $admin): Vehicle
    {
        return DB::transaction(function () use ($vehicle, $admin) {
            $old = $vehicle->only(['status']);

            $vehicle->forceFill(['status' => VehicleStatus::Suspended])->save();

            $this->auditLog->log($admin, 'vehicle.suspended', $vehicle, $old, ['status' => VehicleStatus::Suspended->value]);

            return $vehicle;
        });
    }
}
