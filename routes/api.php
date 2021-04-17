<?php

use App\Http\Controllers\AuthenticationController;
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


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', 'AuthenticationController@login');
    Route::post('/register', 'AuthenticationController@register');
    Route::post('/logout', 'AuthenticationController@logout');
    Route::post('/refresh', 'AuthenticationController@refresh');
    Route::post('/reset-password', 'AuthenticationController@resetPassword');
});



Route::group([
    'middleware' => 'api',
    'prefix' => 'v1'

], function ($router) {
    Route::get('/profile', 'AuthenticationController@userProfile');
    Route::resource('/categories', 'CategoryController');
    Route::get('/categories/{category_id}/subcategories', 'CategoryController@getSubcategoryByCategoryId');
    Route::put('/businesses', 'BusinessController@update');



    //Route::resource('/businesses', 'BusinessController');
    Route::resource('/clients', 'ClientController');
    Route::resource('/couriers', 'CourierController');
    Route::resource('/addresses', 'AdressController');
    Route::resource('/clients', "ClientController");
    Route::resource('/products', 'ProductController');
    Route::resource('/orders', 'OrderController');
    Route::resource('/ratings', 'RatingController');
});
