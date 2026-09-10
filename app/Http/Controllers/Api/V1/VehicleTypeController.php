<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Concerns\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Resources\VehicleTypeResource;
use App\Models\VehicleType;
use Illuminate\Http\JsonResponse;

class VehicleTypeController extends Controller
{
    use ApiResponses;

    public function index(): JsonResponse
    {
        $vehicleTypes = VehicleType::where('status', 'active')->get();

        return $this->success(VehicleTypeResource::collection($vehicleTypes), 'Vehicle types retrieved successfully.');
    }
}
