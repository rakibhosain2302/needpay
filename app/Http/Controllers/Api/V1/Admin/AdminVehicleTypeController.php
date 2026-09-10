<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Concerns\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVehicleTypeRequest;
use App\Http\Requests\Admin\UpdateVehicleTypeRequest;
use App\Http\Resources\VehicleTypeResource;
use App\Models\VehicleType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminVehicleTypeController extends Controller
{
    use ApiResponses;

    public function index(Request $request): JsonResponse
    {
        $vehicleTypes = VehicleType::query()->latest()->paginate($request->integer('per_page', 15));

        return $this->success(VehicleTypeResource::collection($vehicleTypes)->response()->getData(true), 'Vehicle types retrieved successfully.');
    }

    public function store(StoreVehicleTypeRequest $request): JsonResponse
    {
        $vehicleType = VehicleType::create($request->validated());

        return $this->success(new VehicleTypeResource($vehicleType), 'Vehicle type created successfully.', 201);
    }

    public function update(UpdateVehicleTypeRequest $request, VehicleType $vehicleType): JsonResponse
    {
        $vehicleType->update($request->validated());

        return $this->success(new VehicleTypeResource($vehicleType->fresh()), 'Vehicle type updated successfully.');
    }

    public function destroy(VehicleType $vehicleType): JsonResponse
    {
        $vehicleType->delete();

        return $this->success(null, 'Vehicle type deleted successfully.');
    }
}
