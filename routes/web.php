<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('products', ProductsController::class);
Route::post('products/generate', [ProductsController::class, 'generate'])->name('products.generate');
Route::put('products/{id}/categories/attach', [ProductsController::class, 'attachCategories'])->name('products.categories');
Route::resource('categories', CategoriesController::class);
