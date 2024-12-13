<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\CourierController;
use App\Http\Controllers\Admin\FinancialReportController;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CourierReportController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderReportController;
use App\Http\Controllers\Admin\CourierManagementController;
use App\Http\Controllers\Auth\AuthCouriersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth:sanctum');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/couriers', [CourierController::class, 'createCourier']);

Route::post('orders', [OrderController::class, 'store']);
Route::put('orders/{orderId}/reassign', [OrderController::class, 'reassignCourier']);
Route::put('orders/{orderId}/accept', [OrderController::class, 'acceptOrder']);
Route::put('orders/{orderId}/confirm', [OrderController::class, 'confirmOrder']);

Route::middleware(['auth:sanctum'])->post('/admin/couriers/{courierId}/update-location', [AdminController::class, 'updateCourierLocation']);
Route::middleware(['auth:sanctum'])->post('client/couriers/{courierId}/update-location', [ClientController::class, 'updateCourierLocation']);

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/restaurants', [RestaurantController::class, 'store']);
    Route::post('/couriers', [CourierController::class, 'store']);
    Route::resource('restaurants', RestaurantController::class);
    Route::resource('couriers', CourierController::class);
});

Route::middleware(['auth:sanctum', 'role:courier'])->group(function () {
    Route::post('/orders/{orderId}/confirm', [OrderController::class, 'confirmOrder']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::put('order/{order}/arrived-at-restaurant', [CourierController::class, 'arrivedAtRestaurant']);
    Route::put('order/{order}/picked-up-from-client', [CourierController::class, 'pickedUpFromClient']);
    Route::put('order/{order}/delivered', [CourierController::class, 'delivered']);
});

Route::post('/courier/login', [CourierController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/courier/report', [CourierReportController::class, 'dailyReport']);
    Route::get('/courier/orders', [CourierReportController::class, 'orderHistory']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/admin/reports/financial', [AdminReportController::class, 'getFinancialReport']);
});

Route::get('/assign-courier', [CourierController::class, 'assignCourier']);
Route::get('/admin/reports', [AdminReportController::class, 'index']);
Route::get('/couriers/active', [CourierController::class, 'active']);
Route::get('/orders', [OrderController::class, 'index']);

Route::get('/admin/reports/courier-stats', [AdminReportController::class, 'courierStats']);
Route::get('/couriers/orders', [CourierController::class, 'getCouriersWithOrders']);
Route::get('/admin/reports/courier-stats', [CourierController::class, 'getCourierStats']);

Route::get('/financial-reports', [FinancialReportController::class, 'getReport']);
Route::get('/api/admin/reports/orders', [OrderReportController::class, 'getOrders']);
Route::get('/orders/reports/orders', [OrderController::class, 'getReportTable'])->name('orders.report.table');

Route::prefix('admin/couriers')->group(function () {
    Route::get('/', [CourierController::class, 'index']);
    Route::post('/', [CourierController::class, 'store']);
    Route::put('/{courier}', [CourierController::class, 'update']);
    Route::delete('/{courier}', [CourierController::class, 'destroy']);
    Route::post('/{courier}/pause', [CourierController::class, 'pause']);
    Route::post('/{courier}/end-day', [CourierController::class, 'endDay']);
});

Route::prefix('courier')->group(function () {
    Route::post('/login', [AuthCouriersController::class, 'login']);
    Route::post('/logout', [AuthCouriersController::class, 'logout'])->middleware('auth:courier');
    Route::get('/profile', [AuthCouriersController::class, 'profile'])->middleware('auth:courier');
});

Route::prefix('admin')->group(function () {
    Route::get('/couriers', [CourierManagementController::class, 'index']);
    Route::post('/couriers', [CourierManagementController::class, 'store']);
    Route::delete('/couriers/{id}', [CourierManagementController::class, 'destroy']);
});
