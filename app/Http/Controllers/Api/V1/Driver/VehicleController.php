<?php

namespace App\Http\Controllers\Api\V1\Driver;

use App\Http\Concerns\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Driver\StoreVehicleRequest;
use App\Http\Requests\Driver\UpdateVehicleRequest;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use App\Services\VehicleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    use ApiResponses;

    public function __construct(private readonly VehicleService $vehicleService) {}

    public function index(Request $request): JsonResponse
    {
        $driver = $request->user()->driver()->firstOrFail();

        $vehicles = $driver->vehicles()->with('vehicleType')->latest()->get();

        return $this->success(VehicleResource::collection($vehicles), 'Vehicles retrieved successfully.');
    }

    public function store(StoreVehicleRequest $request): JsonResponse
    {
        $driver = $request->user()->driver()->firstOrFail();

        $vehicle = $this->vehicleService->create($driver, $request->validated(), $request->file('photo'));

        return $this->success(new VehicleResource($vehicle->load('vehicleType')), 'Vehicle submitted successfully.', 201);
    }

    public function show(Vehicle $vehicle): JsonResponse
    {
        $this->authorize('view', $vehicle);

        return $this->success(new VehicleResource($vehicle->load('vehicleType')), 'Vehicle retrieved successfully.');
    }

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle): JsonResponse
    {
        $this->authorize('update', $vehicle);

        $vehicle = $this->vehicleService->update($vehicle, $request->validated(), $request->file('photo'));

        return $this->success(new VehicleResource($vehicle->load('vehicleType')), 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle): JsonResponse
    {
        $this->authorize('delete', $vehicle);

        $vehicle->delete();

        return $this->success(null, 'Vehicle deleted successfully.');
    }
}
