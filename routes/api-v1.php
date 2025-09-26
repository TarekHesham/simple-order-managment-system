<?php

use App\Http\Controllers\Api\OrderController;
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
Route::middleware('role:admin')->prefix('admin')->group(function () {
    // Customers CRUD
    Route::apiResource('customers', CustomerController::class);

    // Products CRUD
    Route::apiResource('products', ProductController::class);

    // Order Management
    Route::controller(OrderController::class)
        ->prefix('orders')
        ->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::post('/{order}/refund', 'refundOrder');
            Route::post('/{order}/items/{item}/refund', 'refundItem');
        });
});

// Staff routes
Route::middleware('role:staff|admin')->group(function () {
    // Customers
    Route::apiResource('customers', StaffCustomerController::class)->only('index', 'show');

    // Products
    Route::apiResource('products', StaffProductController::class)->only('index');

    // Order Management
    Route::controller(OrderController::class)
        ->prefix('orders')
        ->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::post('/{order}/refund', 'refundOrder');
            Route::post('/{order}/items/{item}/refund', 'refundItem');
        });
});
