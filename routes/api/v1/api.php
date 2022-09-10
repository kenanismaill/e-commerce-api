<?php

use App\Http\Controllers\Api\V1\DiscountOrder\DiscountOrderController;
use App\Http\Controllers\Api\V1\Order\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::apiResource('orders', OrderController::class)->only('index','store','destroy');
Route::get('orders/{order}/discounts', [DiscountOrderController::class, 'index'])->name('orders.discounts');
