<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes v1
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Api\V1\Admin\{
    CustomerController,
    ProductController
};
use App\Http\Controllers\Api\V1\Staff\{
    CustomerController as StaffCustomerController,
    ProductController as StaffProductController
};
use App\Http\Controllers\Api\V1\Shared\AuthController;

// Authintication routes
Route::withoutMiddleware('auth:sanctum')->prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
});

// Public routes
Route::withoutMiddleware('auth:sanctum')->group(function () {
    // Here you can add public routes that don't require authentication
});

// Admin routes
Route::middleware('role:super-admin|admin')->prefix('admin')->group(function () {
    // Customers CRUD
    Route::apiResource('customers', CustomerController::class);

    // Products CRUD
    Route::apiResource('products', ProductController::class);
});

// Staff routes
Route::middleware('role:staff')->group(function () {
    // Customers
    Route::apiResource('customers', StaffCustomerController::class)->only('index', 'show');

    // Products
    Route::apiResource('products', StaffProductController::class)->only('index');
});
