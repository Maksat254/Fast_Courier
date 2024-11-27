<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CourierController;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\CourierLocationController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CourierReportController;


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

Route::middleware(['auth:sanctum',])->post('/admin/couriers/{courierId}/update-location', [AdminController::class, 'updateCourierLocation']);
Route::middleware(['auth:sanctum',])->post('client/couriers/{courierId}/update-location', [ClientController::class, 'updateCourierLocation']);

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

Route::get('/assign-courier', [CourierController::class, 'assignCourier']);
