<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::name('api.')->group(function () {
    Route::resource('products', ProductsController::class);
    Route::post('products/generate', [ProductsController::class, 'generate'])->name('products.generate');
    Route::put('products/{id}/categories/attach', [ProductsController::class, 'attachCategories'])->name('products.categories');
    Route::resource('categories', CategoriesController::class);
});
