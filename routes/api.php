<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

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

Route::prefix('v1')->group(function () {

    Route::post('/token', 'App\Http\Controllers\UserController@token');
    Route::post('/login', 'App\Http\Controllers\UserController@login');

    // R for Products
    Route::controller(ProductController::class)->group(function () {
        Route::get('/products', 'index');
        Route::get('/product/{slug}', 'show');
    });

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories', 'index');
        Route::get('/category/{slug}', 'show');
    });

    Route::controller(OrderController::class)->group(function () {
        Route::post('/order', 'create');
    });

    Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
        Route::post('/logout', 'App\Http\Controllers\UserController@logout');

        Route::controller(\App\Http\Controllers\Admin\ProductController::class)->group(function () {
            Route::get('/products', 'index');
            Route::get('/product/{id}', 'show');
            Route::post('/product', 'create');
            Route::put('/product/{id}', 'update');
            Route::delete('/product/{id}', 'delete');
        });

        Route::controller(\App\Http\Controllers\Admin\CategoryController::class)->group(function () {
            Route::get('/categories', 'index');
            Route::get('/category/{id}', 'show');
            Route::post('/category', 'create');
            Route::put('/category/{id}', 'update');
            Route::delete('/category/{id}', 'delete');
        });

        Route::controller(\App\Http\Controllers\Admin\OrderController::class)->group(function () {
            Route::get('/orders', 'index');
            Route::get('/order/{id}', 'show');
            Route::put('/order/{id}', 'update');
        });
    });
});
