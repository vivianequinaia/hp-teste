<?php

use Illuminate\Http\Request;
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

/*
| Product Routes
*/
Route::get('/products', 'ProductController@index')->name('product-index');
Route::get('/products/{id}', 'ProductController@show')->name('product-show');
Route::post('/products', 'ProductController@store')->name('product-store');
Route::delete('/product/{id}', 'ProductController@destroy')->name('product-delete');

/*
| Purchase Routes
*/
Route::post('/purchase', 'PurchaseController@shopping')->name('purchase-shopping');
