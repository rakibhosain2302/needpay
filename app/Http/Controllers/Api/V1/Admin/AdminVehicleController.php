<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Concerns\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RejectVehicleRequest;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use App\Services\VehicleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminVehicleController extends Controller
{
    use ApiResponses;

    public function __construct(private readonly VehicleService $vehicleService) {}

    public function index(Request $request): JsonResponse
    {
        $query = Vehicle::query()->with(['driver.user', 'vehicleType']);

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($driverId = $request->query('driver_id')) {
            $query->where('driver_id', $driverId);
        }

        $vehicles = $query->latest()->paginate($request->integer('per_page', 15));

        return $this->success(VehicleResource::collection($vehicles)->response()->getData(true), 'Vehicles retrieved successfully.');
    }

    public function show(Vehicle $vehicle): JsonResponse
    {
        return $this->success(new VehicleResource($vehicle->load(['driver.user', 'vehicleType'])), 'Vehicle retrieved successfully.');
    }

    public function approve(Request $request, Vehicle $vehicle): JsonResponse
    {
        $vehicle = $this->vehicleService->approve($vehicle, $request->user());

        return $this->success(new VehicleResource($vehicle), 'Vehicle approved successfully.');
    }

    public function reject(RejectVehicleRequest $request, Vehicle $vehicle): JsonResponse
    {
        $vehicle = $this->vehicleService->reject($vehicle, $request->user(), $request->validated('rejection_reason'));

        return $this->success(new VehicleResource($vehicle), 'Vehicle rejected successfully.');
    }

    public function suspend(Request $request, Vehicle $vehicle): JsonResponse
    {
        $vehicle = $this->vehicleService->suspend($vehicle, $request->user());

        return $this->success(new VehicleResource($vehicle), 'Vehicle suspended successfully.');
    }
}
