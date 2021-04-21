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

    //Finished
    Route::post('/login', 'AuthenticationController@login');
    Route::post('/register', 'AuthenticationController@register');
    Route::post('/logout', 'AuthenticationController@logout');
    Route::post('/refresh', 'AuthenticationController@refresh');
    Route::post('/reset-password', 'AuthenticationController@resetPassword');
    Route::put('/password', 'AuthenticationController@updatePassword');

});



Route::group([
    'middleware' => 'api',
    'prefix' => 'v1'

], function ($router) {

    //Finished
    Route::get('/profile', 'AuthenticationController@userProfile');
    Route::resource('/categories', 'CategoryController');
    Route::get('/categories/{category_id}/subcategories', 'CategoryController@getSubcategoryByCategoryId');
    Route::post('/businesses', 'BusinessController@update');
    Route::post('/couriers', 'CourierController@update');
    Route::post('/clients', 'ClientController@update');
    Route::post('/photo', 'ClientController@uploadPhoto');

    //Working
    //Products
    Route::get('/products/{business_id}' , 'ProductController@getProducts');
    Route::get('/products{product_id}' , 'ProductController@getProductById');
    Route::post('/products', 'ProductController@createProduct');
    Route::post('/products/{product_id}' , 'ProductController@updateProduct');
    Route::delete('/products{product_id}' , 'ProductController@createProduct');





});
