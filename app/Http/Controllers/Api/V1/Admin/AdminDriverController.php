<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Concerns\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RejectDriverRequest;
use App\Http\Resources\DriverResource;
use App\Models\Driver;
use App\Services\DriverVerificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminDriverController extends Controller
{
    use ApiResponses;

    public function __construct(private readonly DriverVerificationService $verificationService) {}

    public function index(Request $request): JsonResponse
    {
        $query = Driver::query()->with('user');

        if ($status = $request->query('verification_status')) {
            $query->where('verification_status', $status);
        }

        if ($availability = $request->query('availability_status')) {
            $query->where('availability_status', $availability);
        }

        $drivers = $query->latest()->paginate($request->integer('per_page', 15));

        return $this->success(DriverResource::collection($drivers)->response()->getData(true), 'Drivers retrieved successfully.');
    }

    public function show(Driver $driver): JsonResponse
    {
        return $this->success(new DriverResource($driver->load(['user', 'documents', 'vehicles'])), 'Driver retrieved successfully.');
    }

    public function approve(Request $request, Driver $driver): JsonResponse
    {
        $driver = $this->verificationService->approve($driver, $request->user());

        return $this->success(new DriverResource($driver), 'Driver approved successfully.');
    }

    public function reject(RejectDriverRequest $request, Driver $driver): JsonResponse
    {
        $driver = $this->verificationService->reject($driver, $request->user(), $request->validated('rejection_reason'));

        return $this->success(new DriverResource($driver), 'Driver rejected successfully.');
    }

    public function suspend(Request $request, Driver $driver): JsonResponse
    {
        $driver = $this->verificationService->suspend($driver, $request->user());

        return $this->success(new DriverResource($driver), 'Driver suspended successfully.');
    }
}
