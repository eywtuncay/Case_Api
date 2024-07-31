<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\DiscountResponseController;

use App\Http\Controllers\Api\DiscountController;


Route::apiResource('products', ProductController::class);
Route::apiResource('customers', CustomerController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('discount-responses', DiscountResponseController::class);

Route::get('/orders/{orderId}/discounts', [DiscountController::class, 'calculateDiscounts']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
