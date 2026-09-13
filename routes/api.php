<?php

use App\Http\Controllers\Api\V1\Admin\AdminDriverController;
use App\Http\Controllers\Api\V1\Admin\AdminDriverDocumentController;
use App\Http\Controllers\Api\V1\Admin\AdminVehicleController;
use App\Http\Controllers\Api\V1\Admin\AdminVehicleTypeController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\Driver\DriverController;
use App\Http\Controllers\Api\V1\Driver\DriverDocumentController;
use App\Http\Controllers\Api\V1\Driver\DriverStatusController;
use App\Http\Controllers\Api\V1\Driver\VehicleController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\VehicleTypeController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register'])->middleware('throttle:10,1');
        Route::post('login', [AuthController::class, 'login'])->middleware('throttle:5,1');
        Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:5,1');
        Route::post('reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:5,1');

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('me', [AuthController::class, 'me']);
            Route::post('logout', [AuthController::class, 'logout']);
            Route::post('logout-all', [AuthController::class, 'logoutAll']);
            Route::post('change-password', [AuthController::class, 'changePassword']);
        });
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('profile', [ProfileController::class, 'show']);
        Route::put('profile', [ProfileController::class, 'update']);
    });

    // Minimal probes demonstrating account-type authorization; real endpoints land in later phases.
    Route::middleware(['auth:sanctum', 'account.type:passenger'])
        ->get('passenger/ping', fn () => response()->json(['success' => true, 'message' => 'passenger ok']));
    Route::middleware(['auth:sanctum', 'account.type:driver'])
        ->get('driver/ping', fn () => response()->json(['success' => true, 'message' => 'driver ok']));
    Route::middleware(['auth:sanctum', 'account.type:admin'])
        ->get('admin/ping', fn () => response()->json(['success' => true, 'message' => 'admin ok']));

    Route::get('vehicle-types', [VehicleTypeController::class, 'index']);

    Route::middleware(['auth:sanctum', 'account.type:driver'])->prefix('driver')->group(function () {
        Route::get('profile', [DriverController::class, 'show']);
        Route::put('profile', [DriverController::class, 'update']);

        Route::get('documents', [DriverDocumentController::class, 'index']);
        Route::post('documents', [DriverDocumentController::class, 'store']);
        Route::put('documents/{document}', [DriverDocumentController::class, 'update']);
        Route::delete('documents/{document}', [DriverDocumentController::class, 'destroy']);

        Route::post('online', [DriverStatusController::class, 'online']);
        Route::post('offline', [DriverStatusController::class, 'offline']);
        Route::get('status', [DriverStatusController::class, 'status']);

        Route::get('vehicles', [VehicleController::class, 'index']);
        Route::post('vehicles', [VehicleController::class, 'store']);
        Route::get('vehicles/{vehicle}', [VehicleController::class, 'show']);
        Route::put('vehicles/{vehicle}', [VehicleController::class, 'update']);
        Route::delete('vehicles/{vehicle}', [VehicleController::class, 'destroy']);
    });

    Route::middleware(['auth:sanctum', 'account.type:admin'])->prefix('admin')->group(function () {
        Route::middleware('permission:drivers.view')->group(function () {
            Route::get('drivers', [AdminDriverController::class, 'index']);
            Route::get('drivers/{driver}', [AdminDriverController::class, 'show']);
            Route::get('driver-documents', [AdminDriverDocumentController::class, 'index']);
            Route::get('driver-documents/{document}', [AdminDriverDocumentController::class, 'show']);
            Route::get('driver-documents/{document}/file', [AdminDriverDocumentController::class, 'file']);
        });

        Route::middleware('permission:drivers.verify')->group(function () {
            Route::post('drivers/{driver}/approve', [AdminDriverController::class, 'approve']);
            Route::post('drivers/{driver}/reject', [AdminDriverController::class, 'reject']);
            Route::post('driver-documents/{document}/approve', [AdminDriverDocumentController::class, 'approve']);
            Route::post('driver-documents/{document}/reject', [AdminDriverDocumentController::class, 'reject']);
        });

        Route::middleware('permission:drivers.suspend')
            ->post('drivers/{driver}/suspend', [AdminDriverController::class, 'suspend']);

        Route::middleware('permission:vehicles.view')->group(function () {
            Route::get('vehicles', [AdminVehicleController::class, 'index']);
            Route::get('vehicles/{vehicle}', [AdminVehicleController::class, 'show']);
        });

        Route::middleware('permission:vehicles.verify')->group(function () {
            Route::post('vehicles/{vehicle}/approve', [AdminVehicleController::class, 'approve']);
            Route::post('vehicles/{vehicle}/reject', [AdminVehicleController::class, 'reject']);
            Route::post('vehicles/{vehicle}/suspend', [AdminVehicleController::class, 'suspend']);
        });

        Route::middleware('permission:vehicle_types.manage')->group(function () {
            Route::get('vehicle-types', [AdminVehicleTypeController::class, 'index']);
            Route::post('vehicle-types', [AdminVehicleTypeController::class, 'store']);
            Route::put('vehicle-types/{vehicleType}', [AdminVehicleTypeController::class, 'update']);
            Route::delete('vehicle-types/{vehicleType}', [AdminVehicleTypeController::class, 'destroy']);
        });
    });
});
