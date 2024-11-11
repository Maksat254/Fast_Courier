<?php

use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth:sanctum');


Route::post('/orders/{order}/decline', [OrderController::class, 'declineOrder'])
    ->middleware('auth:sanctum')
    ->name('orders.decline');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus']);
    Route::post('orders', [OrderController::class, 'store']);
    Route::put('orders/{order}/reassign', [OrderController::class, 'reassignCourier']);
});

