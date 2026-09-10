<?php

namespace App\Http\Controllers\Api\V1\Driver;

use App\Enums\DriverAvailabilityStatus;
use App\Http\Concerns\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Resources\DriverResource;
use App\Services\DriverVerificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DriverStatusController extends Controller
{
    use ApiResponses;

    public function __construct(private readonly DriverVerificationService $verificationService) {}

    public function status(Request $request): JsonResponse
    {
        $driver = $request->user()->driver()->firstOrFail();

        return $this->success(new DriverResource($driver), 'Driver status retrieved successfully.');
    }

    public function online(Request $request): JsonResponse
    {
        $driver = $request->user()->driver()->firstOrFail();

        $this->verificationService->assertCanGoOnline($driver);

        $driver->forceFill([
            'is_online' => true,
            'availability_status' => DriverAvailabilityStatus::Online,
        ])->save();

        return $this->success(new DriverResource($driver->fresh()), 'You are now online.');
    }

    public function offline(Request $request): JsonResponse
    {
        $driver = $request->user()->driver()->firstOrFail();

        if (in_array($driver->availability_status, [DriverAvailabilityStatus::Busy, DriverAvailabilityStatus::OnTrip], true)) {
            throw ValidationException::withMessages([
                'driver' => ['You cannot go offline while busy or on a trip.'],
            ]);
        }

        $driver->forceFill([
            'is_online' => false,
            'availability_status' => DriverAvailabilityStatus::Offline,
        ])->save();

        return $this->success(new DriverResource($driver->fresh()), 'You are now offline.');
    }
}
