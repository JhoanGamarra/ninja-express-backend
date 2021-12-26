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

Route::group(
    [
        'middleware' => 'api',
        'prefix' => 'auth',
    ],
    function ($router) {
        //Finished
        Route::post('/login', 'AuthenticationController@login');
        Route::post('/register', 'AuthenticationController@register');
        Route::post('/logout', 'AuthenticationController@logout');
        Route::post('/refresh', 'AuthenticationController@refresh');
        Route::post(
            '/reset-password',
            'AuthenticationController@resetPassword'
        );
        Route::put('/password', 'AuthenticationController@updatePassword');
    }
);

Route::group(
    [
        'middleware' => 'api',
        'prefix' => 'v1',
    ],
    function ($router) {
        //TODO  (Try Catch , Response Codes, Validations , Variables Refactoring, SecurityForEndpoints)

        //Address
        Route::post('/addresses', 'AddressController@createAddress');
        Route::get(
            '/addresses/{address_id}',
            'AddressController@getAddressById'
        );
        //working/in testing
        Route::put(
            '/addresses/{address_id}',
            'AddressController@updateAddress'
        );
        //working/in testing
        //Route::get('/businesses/{business_id}/addresses', 'AddressController@getBussinessAddresses');
        Route::get(
            '/clients/{client_id}/addresses',
            'AddressController@getClientAddresses'
        );

        //clients
        Route::post('/clients', 'ClientController@update');
        Route::get('/clients', 'ClientController@getAll');

        //Couriers
        Route::post('/couriers', 'CourierController@update');
        Route::get('/couriers', 'CourierController@getAll');
        Route::put('/couriers/{courier_id}', 'CourierController@updateStatus');

        //Businesses
        Route::post('/businesses', 'BusinessController@update');
        Route::get('/businesses', 'BusinessController@getAll');
        Route::get(
            '/categories/{category_id}/businesses',
            'BusinessController@getBusinessesByCategory'
        );
        Route::put(
            '/businesses/{business_id}/available',
            'BusinessController@changeAvailableStatus'
        );

        //Categories
        Route::get('/categories', 'CategoryController@getCategories');
        Route::get(
            '/categories/{category_id}/subcategories',
            'CategoryController@getSubcategoryByCategoryId'
        );
        Route::post(
            '/categories/{category_id}',
            'CategoryController@updateCategory'
        );

        //Profile
        Route::get('/profile', 'AuthenticationController@userProfile');
        Route::post('/photo', 'ClientController@uploadPhoto');

        //Products
        Route::get(
            '/businesses/{business_id}/products/',
            'ProductController@getProducts'
        ); //changed the route  business to a businesses
        Route::get(
            '/products/{product_id}',
            'ProductController@getProductById'
        );
        Route::post(
            '/products/{product_id}',
            'ProductController@updateProduct'
        );
        Route::post('/products', 'ProductController@createProduct');

        //Orders
        Route::get(
            '/clients/{client_id}/orders',
            'OrderController@getClientOrders'
        );
        Route::post('/orders', 'OrderController@createOrder');
        Route::get('/orders', 'OrderController@getAll');
        Route::put('/orders/{order_id}', 'OrderController@updateOrder');
        Route::get('/orders/{order_id}', 'OrderController@getOrderById');
        Route::get(
            '/businesses/{business_id}/orders',
            'OrderController@getBusinessOrders'
        );
        Route::get(
            '/clients/{client_id}/orders',
            'OrderController@getClientOrders'
        );

        //PUSH NOTIFICATIONS
        Route::post('/push', 'OrderController@sendPush');

        //Working
        Route::post(
            '/distance',
            'OrderController@haversineGreatCircleDistance'
        );
        Route::post('/geocoder', 'OrderController@getCordinatesFromAddress');
        Route::post('/distancedriving', 'OrderController@GetDrivingDistance');
    }
);

Route::group(
    [
        'middleware' => 'api',
        'prefix' => 'v2',
    ],
    function ($router) {
        Route::get('/categories', 'CategoryController@getCategories');
        Route::post('/auth/login', 'AuthenticationController@login');
    }
);
