<?php

declare(strict_types=1);

use App\Http\Controllers\Api\LaundryOrderController;
use Illuminate\Support\Facades\Route;

Route::post('/orders', [LaundryOrderController::class, 'store']);
