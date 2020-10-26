<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', 'DashboardController@index');

Route::get('/products', 'ProductsController@index');
Route::post('/products/add-new-product', 'ProductsController@addNewProd');
Route::post('/products/save-product', 'ProductsController@saveProd');
Route::get('/products/get-prod-types-list', 'ProductsController@getProdTypesList');
Route::get('/products/get-prods-list', 'ProductsController@getProdsList');
Route::get('/products/delete-prod', 'ProductsController@deleteProd');

Route::get('/pizza-sets', 'PizzaSetsController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
