<?php

use App\Http\Controllers\Admin\CourierController;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\CourierLocationController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth:sanctum');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('orders', [OrderController::class, 'store']);
Route::put('orders/{orderId}/reassign', [OrderController::class, 'reassignCourier']);
Route::put('orders/{orderId}/accept', [OrderController::class, 'acceptOrder']);
Route::put('orders/{orderId}/confirm', [OrderController::class, 'confirmOrder']);

Route::middleware('auth:sanctum')->post('/courier/update-location', [CourierLocationController::class, 'updateLocation']);

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


