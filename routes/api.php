<?php

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

Route::post('auth/login', 'Admin\AuthController@login');
Route::post('auth/register', 'Admin\AuthController@register');
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('auth/user', 'Admin\AuthController@user');
    Route::post('auth/logout', 'Admin\AuthController@logout');

    // Product
    Route::apiResource('products', 'Admin\Product\ProductController');
    // Category
    Route::get('categories/autocomplete', 'Admin\Product\CategoryController@autocomplete');
    Route::apiResource('categories', 'Admin\Product\CategoryController');
});

