<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProfileController;
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
    Route::middleware(['auth:sanctum', 'account.type:customer'])
        ->get('customer/ping', fn () => response()->json(['success' => true, 'message' => 'customer ok']));
    Route::middleware(['auth:sanctum', 'account.type:driver'])
        ->get('driver/ping', fn () => response()->json(['success' => true, 'message' => 'driver ok']));
    Route::middleware(['auth:sanctum', 'account.type:admin'])
        ->get('admin/ping', fn () => response()->json(['success' => true, 'message' => 'admin ok']));
});
