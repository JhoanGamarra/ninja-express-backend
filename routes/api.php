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

Route::middleware('auth:api')->get('/user', function (Request $request) {//
    return $request->user();
});

//Auth

//Route::post('/login', [AuthenticationController::class, 'login']);
//Route::post('/login', 'AuthenticationController@login');

Route::get('/test', function (Request $request) {
    return "test";
});


Route::resource('busineses', 'BusinessController');
Route::middleware('auth:api')->resource('clients', 'ClientController');
Route::middleware('auth:api')->resource('couriers', 'CourierController');
Route::middleware('auth:api')->resource('addresses', 'AdressController');

Route::middleware('auth:api')->resource('clients', "ClientController");
Route::middleware('auth:api')->resource('products', 'ProductController');
Route::middleware('auth:api')->resource('orders', 'OrderController');
Route::middleware('auth:api')->resource('ratings', 'RatingController');
Route::resource('categories', 'CategoryController');
Route::get('categories/{category_id}/subcategories', 'CategoryController@getSubcategoryByCategoryId');



