<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes v1
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Api\V1\Admin\{
    CustomerController,
    ProductController,
    SupplierController
};
use App\Http\Controllers\Api\V1\Staff\{
    CustomerController as StaffCustomerController,
    ProductController as StaffProductController
};
use App\Http\Controllers\Api\V1\Shared\{
    AuthController,
    OrderController
};

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
    // Customers Management
    Route::apiResource('customers', CustomerController::class);

    // Products Management
    Route::apiResource('products', ProductController::class);

    // Suppliers Management
    Route::apiResource('suppliers', SupplierController::class);
});

// Staff routes
Route::middleware('role:staff|admin')->group(function () {
    // Customers
    Route::apiResource('customers', StaffCustomerController::class)->only('index', 'show');

    // Products
    Route::get('products', [StaffProductController::class, 'index']);

    // Suppliers Management
    Route::get('suppliers', [SupplierController::class, 'index']);

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
