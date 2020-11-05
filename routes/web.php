<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use \App\Http\Controllers\DashboardController;
use \App\Http\Controllers\ProductsController;
use \App\Http\Controllers\PizzaSetsController;
use \App\Http\Controllers\OrdersController;
use \App\Http\Controllers\CustomersController;
use \App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [DashboardController::class, 'index']);


Route::prefix('products')->group(function() {
    Route::get('', [ProductsController::class, 'index']);
    Route::post('add-new', [ProductsController::class, 'addNew']);
    Route::post('save', [ProductsController::class, 'save']);
    Route::get('get-types-list', [ProductsController::class, 'getTypesList']);
    Route::get('get-list', [ProductsController::class, 'getList']);
    Route::get('delete', [ProductsController::class, 'delete']);
});


Route::prefix('pizza-sets')->group(function() {
    Route::get('', [PizzaSetsController::class, 'index']);
    Route::post('add-new', [PizzaSetsController::class, 'addNew']);
    Route::post('save', [PizzaSetsController::class, 'save']);
    Route::get('get-prods-list', [PizzaSetsController::class, 'getProdsList']);
    Route::get('get-list', [PizzaSetsController::class, 'getList']);
    Route::get('delete', [PizzaSetsController::class, 'delete']);
});


Route::prefix('orders')->group(function() {
    Route::get('', [OrdersController::class, 'index']);
    Route::post('add-new', [OrdersController::class, 'addNew']);
    Route::post('save', [OrdersController::class, 'save']);
    Route::get('get-list', [OrdersController::class, 'getList']);
    Route::get('delete', [OrdersController::class, 'delete']);
});


Route::prefix('customers')->group(function() {
    Route::get('', [CustomersController::class, 'index']);
    Route::post('add-new', [CustomersController::class, 'addNew']);
    Route::post('save', [CustomersController::class, 'save']);
    Route::get('get-list', [CustomersController::class, 'getList']);
    Route::get('delete', [CustomersController::class, 'delete']);
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
