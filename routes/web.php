<?php

declare(strict_types=1);

use App\Models\LaundryOrder;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $orders = LaundryOrder::with('customer')->latest()->take(10)->get();

    return view('welcome', compact('orders'));
});
