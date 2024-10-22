<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\OrderController;


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('prices', PriceController::class);
    Route::post('orders', [OrderController::class, 'store']); // Add to cart
});
