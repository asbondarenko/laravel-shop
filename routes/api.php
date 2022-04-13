<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
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
Route::prefix('v1')->group(
    function () {
        Route::apiResource('products', ProductController::class)->except(['delete']);
        Route::apiResource('categories', CategoryController::class);

        Route::prefix('products')->group(
            function () {
                Route::delete('/{product}/archive', [ProductController::class, 'archive']);
                Route::put('/{id}/restore', [ProductController::class, 'restore']);
            }
        );
    });

