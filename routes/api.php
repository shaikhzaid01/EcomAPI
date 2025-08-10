<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix("v1")->group(function () {
    // Products
Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::post('/products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);

// Cart

Route::post('/cart',[CartController::class,'addToCart']);
Route::get('/cart', [CartController::class, 'listCartItems']);
Route::put('/cart/{cartItem}', [CartController::class, 'updateCartItem']);
Route::delete('/cart/{cartItem}', [CartController::class, 'deleteCartItem']);



});
