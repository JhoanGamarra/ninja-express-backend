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

    //TODO  (Try Catch , Response Codes, Validations , Variables Refactoring, SecurityForEndpoints)

    //Address 
    Route::post('/addresses', 'AddressController@createAddress');
    Route::get('/addresses/{address_id}', 'AddressController@getAddressById');
    Route::put('/addresses/{address_id}', 'AddressController@updateAddress');
    Route::get('/businesses/{business_id}/addresses', 'AddressController@getBussinessAddresses');
    Route::get('/clients/{client_id}/addresses', 'AddressController@getClientAddresses');

    //clients
    Route::post('/clients', 'ClientController@update');

    //Couriers
    Route::post('/couriers', 'CourierController@update');
    Route::put('/couriers/{courier_id}', 'CourierController@updateStatus');


    //Businesses
    Route::post('/businesses', 'BusinessController@update');
    Route::get('/categories/{category_id}/businesses', 'BusinessController@getBusinessesByCategory');


    //Categories
    Route::get('/categories', 'CategoryController@getCategories');
    Route::get('/categories/{category_id}/subcategories', 'CategoryController@getSubcategoryByCategoryId');

    //Profile
    Route::get('/profile', 'AuthenticationController@userProfile');
    Route::post('/photo', 'ClientController@uploadPhoto');

    //Products
    Route::get('/businesses/{business_id}/products/', 'ProductController@getProducts'); //change the route  business to a businesses
    Route::get('/products/{product_id}', 'ProductController@getProductById');
    Route::post('/products/{product_id}', 'ProductController@updateProduct');
    Route::post('/products', 'ProductController@createProduct');


    //Orders
    Route::get('/clients/{client_id}/orders', 'OrderController@getClientOrders');
    Route::post('/orders', 'OrderController@createOrder');
    Route::put('/orders/{order_id}', 'OrderController@updateOrder');
    Route::get('/orders/{order_id}', 'OrderController@getOrderById');
    Route::get('/businesses/{business_id}/orders', 'OrderController@getBusinessOrders');
    Route::get('/clients/{client_id}/orders', 'OrderController@getClientOrders');







    //Working

    Route::post('/distance', 'OrderController@haversineGreatCircleDistance');
    //TODO calculate delivery cost and validate change address in the order real time
});
