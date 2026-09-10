<?php

namespace App\Services;

use App\Enums\DocumentStatus;
use App\Enums\DocumentType;
use App\Enums\DriverAvailabilityStatus;
use App\Enums\DriverVerificationStatus;
use App\Enums\UserStatus;
use App\Enums\VehicleStatus;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DriverVerificationService
{
    /**
     * Document types a driver must have approved before their profile can be verified.
     */
    private const REQUIRED_DOCUMENT_TYPES = [
        DocumentType::DrivingLicense,
        DocumentType::Nid,
        DocumentType::VehicleRegistration,
    ];

    public function __construct(private readonly AuditLogService $auditLog) {}

    public function hasApprovedRequiredDocuments(Driver $driver): bool
    {
        $approvedTypes = $driver->documents()
            ->where('status', DocumentStatus::Approved)
            ->pluck('document_type')
            ->all();

        foreach (self::REQUIRED_DOCUMENT_TYPES as $type) {
            if (! in_array($type, $approvedTypes, true)) {
                return false;
            }
        }

        return true;
    }

    public function hasApprovedVehicleWithValidRegistration(Driver $driver): bool
    {
        return $driver->vehicles()
            ->where('status', VehicleStatus::Approved)
            ->get()
            ->contains(fn ($vehicle) => $vehicle->isRegistrationValid());
    }

    public function canGoOnline(Driver $driver): bool
    {
        $driver->loadMissing('user');

        return $driver->user->status === UserStatus::Active
            && $driver->verification_status === DriverVerificationStatus::Approved
            && $this->hasApprovedVehicleWithValidRegistration($driver);
    }

    public function assertCanGoOnline(Driver $driver): void
    {
        $driver->loadMissing('user');

        if ($driver->user->status !== UserStatus::Active) {
            throw ValidationException::withMessages([
                'driver' => ['Your account is not active.'],
            ]);
        }

        if ($driver->verification_status !== DriverVerificationStatus::Approved) {
            throw ValidationException::withMessages([
                'driver' => ['Your driver profile has not been approved yet.'],
            ]);
        }

        if (! $this->hasApprovedVehicleWithValidRegistration($driver)) {
            throw ValidationException::withMessages([
                'driver' => ['You need at least one approved vehicle with a valid registration to go online.'],
            ]);
        }
    }

    public function approve(Driver $driver, User $admin): Driver
    {
        if (! $this->hasApprovedRequiredDocuments($driver)) {
            throw ValidationException::withMessages([
                'driver' => ['This driver does not have all required documents approved (driving license, NID, vehicle registration).'],
            ]);
        }

        return DB::transaction(function () use ($driver, $admin) {
            $old = $driver->only(['verification_status']);

            $driver->forceFill(['verification_status' => DriverVerificationStatus::Approved])->save();

            $this->auditLog->log($admin, 'driver.approved', $driver, $old, ['verification_status' => DriverVerificationStatus::Approved->value]);

            return $driver;
        });
    }

    public function reject(Driver $driver, User $admin, string $reason): Driver
    {
        return DB::transaction(function () use ($driver, $admin, $reason) {
            $old = $driver->only(['verification_status']);

            $driver->forceFill(['verification_status' => DriverVerificationStatus::Rejected])->save();

            $this->auditLog->log($admin, 'driver.rejected', $driver, $old, [
                'verification_status' => DriverVerificationStatus::Rejected->value,
                'rejection_reason' => $reason,
            ]);

            return $driver;
        });
    }

    public function suspend(Driver $driver, User $admin): Driver
    {
        return DB::transaction(function () use ($driver, $admin) {
            $old = $driver->only(['verification_status', 'is_online', 'availability_status']);

            $driver->forceFill([
                'verification_status' => DriverVerificationStatus::Suspended,
                'is_online' => false,
                'availability_status' => DriverAvailabilityStatus::Offline,
            ])->save();

            $this->auditLog->log($admin, 'driver.suspended', $driver, $old, [
                'verification_status' => DriverVerificationStatus::Suspended->value,
                'is_online' => false,
                'availability_status' => DriverAvailabilityStatus::Offline->value,
            ]);

            return $driver;
        });
    }
}
