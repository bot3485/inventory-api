<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\IndexController as AuthController;
use App\Http\Controllers\Api\User\IndexController as UserController;
use App\Http\Controllers\Api\Location\IndexController as    LocationController;
use App\Http\Controllers\Api\Vendor\IndexController as VendorController;
use App\Http\Controllers\Api\DeviceType\IndexController as DeviceTypeController;
use App\Http\Controllers\Api\DeviceModel\IndexController as DeviceModelController;
use App\Http\Controllers\Api\Device\IndexController as DeviceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 1. PUBLIC ROUTES (Accessible by everyone)
Route::post('/login', [AuthController::class, 'login']);

// 2. PROTECTED ROUTES (Requires a valid Bearer token)
Route::middleware('auth:sanctum')->group(function () {
    /**
     * Get Current User
     * Retrieve the profile information of the currently authenticated user.
     * * @group Authentication
     * @authenticated
     * @responseFile storage/responses/me.json
     */
    Route::get('/me', function (Request $request) {
        return $request->user();
    });

    // Authentication actions
    Route::post('/logout', [AuthController::class, 'logout']);

    // User management resources (CRUD)
    Route::apiResource('users', UserController::class);
    // Location management resources (CRUD)
    Route::apiResource('locations', LocationController::class);
    Route::apiResource('vendors', VendorController::class);
    Route::apiResource('device-types', DeviceTypeController::class);
    Route::apiResource('device-models', DeviceModelController::class);
    Route::apiResource('devices', DeviceController::class);
});
