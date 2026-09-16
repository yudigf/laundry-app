<?php

declare(strict_types=1);

use App\Http\Controllers\Api\LaundryOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/orders', [LaundryOrderController::class, 'index']);
Route::post('/orders', [LaundryOrderController::class, 'store']);
Route::patch('/orders/{order}/status', [LaundryOrderController::class, 'updateStatus']);
Route::put('/orders/{order}/status', [LaundryOrderController::class, 'updateStatus']);
Route::patch('/orders/{order}/payment', [LaundryOrderController::class, 'updatePayment']);
