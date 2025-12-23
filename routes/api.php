<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// user
    Route::post('/register',[AuthController::class, 'register']);
    Route::get('/email/verify/{id}/{hash}',[AuthController::class, 'verifyEmail'])->middleware(['signed'])->name('verification.verify');
    Route::post('/login',[AuthController::class, 'login']);
    Route::post('/logout',[AuthController::class, 'logout'])->middleware('auth:sanctum');
//end user
// product
Route::get('/products', [ProductController::class, 'index']);
    Route::middleware('auth:sanctum')->group(function(){
        Route::post('/product', [ProductController::class, 'store']);
        Route::get('/product/{id}', [ProductController::class, 'show']);
        Route::put('/product/{id}', [ProductController::class, 'update']);
        Route::delete('/product/{id}', [ProductController::class, 'destroy']);
    });
// end product 
// category
    Route::middleware('auth:sanctum')->group(function(){
        Route::get('/categories',[CategoryController::class, 'index']);
        Route::post('/category' ,[CategoryController::class, 'store']);
        Route::get('/category/{id}',[CategoryController::class, 'show']);
        Route::put('/category/{id}',[CategoryController::class, 'update']);
        Route::delete('/category/{id}',[CategoryController::class, 'destroy']);
    });
    // end category
    // carts
        Route::middleware('auth:sanctum')->group(function(){
            Route::get('/cart', [CartController::class, 'index']);
            Route::post('/cart',[CartController::class, 'store']);
            Route::put('/cart/{id}',[CartController::class, 'update']);
            Route::delete('/cart/{id}',[CartController::class, 'destroy']);
            Route::delete('/carts',[CartController::class, 'clear']);
        });
    //end Cart
    // orders
    Route::middleware('auth:sanctum')->group(function(){
        Route::post('/checkout',[OrderController::class, 'checkOut']);
        Route::get('/orders',[OrderController::class, 'index']);
        Route::get('/order/{id}',[OrderController::class, 'show']);
        Route::post('/order/{id}/cancel',[OrderController::class, 'cancel']);
    });