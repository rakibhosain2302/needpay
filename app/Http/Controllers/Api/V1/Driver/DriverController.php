<?php

namespace App\Http\Controllers\Api\V1\Driver;

use App\Http\Concerns\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Driver\UpdateDriverProfileRequest;
use App\Http\Resources\DriverResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    use ApiResponses;

    public function show(Request $request): JsonResponse
    {
        $driver = $request->user()->driver()->firstOrFail();
        $this->authorize('view', $driver);

        return $this->success(new DriverResource($driver->load(['documents', 'vehicles'])), 'Driver profile retrieved successfully.');
    }

    public function update(UpdateDriverProfileRequest $request): JsonResponse
    {
        $driver = $request->user()->driver()->firstOrFail();
        $this->authorize('update', $driver);

        $driver->update($request->validated());

        return $this->success(new DriverResource($driver->fresh()), 'Driver profile updated successfully.');
    }
}
