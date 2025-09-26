<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes v1
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Api\V1\Admin\{
    CustomerController
};
use App\Http\Controllers\Api\V1\Staff\{
    CustomerController as StaffCustomerController
};
use App\Http\Controllers\Api\V1\Shared\AuthController;

// Public routes
Route::withoutMiddleware('auth:sanctum')->prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
});

// Admin routes
Route::middleware('role:super-admin|admin')->prefix('admin')->group(function () {
    Route::apiResource('customers', CustomerController::class);
});

// Staff routes
Route::middleware('role:staff')->group(function () {
    Route::apiResource('customers', StaffCustomerController::class)->only('index', 'show');
});
