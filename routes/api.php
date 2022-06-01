<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'middleware' => 'api',
        'prefix' => 'auth',
    ],
    function ($router) {
        $router->post('/login', 'AuthenticationController@login');
        $router->post('/register', 'AuthenticationController@register');
        $router->post('/logout', 'AuthenticationController@logout');
        $router->post('/refresh', 'AuthenticationController@refresh');
        $router->post(
            '/reset-password',
            'AuthenticationController@resetPassword'
        );
        $router->put('/password', 'AuthenticationController@updatePassword');
    }
);

Route::group(
    [
        'middleware' => 'api',
        'prefix' => 'v1',
    ],
    function ($router) {
        //TODO  (Try Catch , Response Codes, Validations , Variables Refactoring, SecurityForEndpoints)

        $router->get(
            '/addresses/{address_id}',
            'AddressController@getAddressById'
        );
        //working/in testing
        $router->put(
            '/addresses/{address_id}',
            'AddressController@updateAddress'
        );
        $router->delete(
            '/addresses/{address_id}',
            'AddressController@deleteAddress'
        );
       
        //Cart 
        $router->get('/clients/{client_id}/carts', 'CartController@getClientCart');
        $router->post('/carts/{cart_id}/items', 'CartController@addItemToCart');
        $router->delete('/carts/items/{item_id}', 'CartController@removeItemToCart');


        //clients
        $router->post('/clients', 'ClientController@update');
        $router->get('/clients', 'ClientController@getAll');
        $router->get('/clients/{client_id}', 'ClientController@getClientById');

        $router->post(
            '/clients/{client_id}/addresses',
            'AddressController@createClientAddress'
        );
        $router->put(
            '/clients/{client_id}/addresses',
            'ClientController@changeCurrentClientAddress'
        );
        
        $router->get(
            '/clients/{client_id}/addresses',
            'AddressController@getClientAddresses'
        );

        //Couriers
        $router->post('/couriers', 'CourierController@update');
        $router->get('/couriers', 'CourierController@getAll');
        $router->put(
            '/couriers/{courier_id}',
            'CourierController@updateStatus'
        );

        //Businesses
        $router->post('/businesses', 'BusinessController@update');
        $router->get('/businesses', 'BusinessController@getAll');
        $router->get(
            '/categories/{category_id}/businesses',
            'BusinessController@getBusinessesByCategory'
        );
        $router->put(
            '/businesses/{business_id}/available',
            'BusinessController@changeAvailableStatus'
        );
        $router->post(
            'businesses/{business_id}/addresses',
            'AddressController@createBusinessAddress'
        );

        //Categories
        $router->get('/categories', 'CategoryController@getCategories');
        $router->get(
            '/categories/{category_id}/subcategories',
            'CategoryController@getSubcategoryByCategoryId'
        );
        $router->post(
            '/categories/{category_id}',
            'CategoryController@updateCategory'
        );

        //Profile
        $router->get('/profile', 'AuthenticationController@userProfile');
        $router->post('/photo', 'ClientController@uploadPhoto');

        //Products
        $router->get(
            '/businesses/{business_id}/products/',
            'ProductController@getProducts'
        ); //changed the route  business to a businesses
        $router->get(
            '/products/{product_id}',
            'ProductController@getProductById'
        );
        $router->post(
            '/products/{product_id}',
            'ProductController@updateProduct'
        );
        $router->post('/products', 'ProductController@createProduct');

        //Orders
        $router->get(
            '/clients/{client_id}/orders',
            'OrderController@getClientOrders'
        );
        $router->post('/orders', 'OrderController@createOrder');
        $router->get('/orders', 'OrderController@getAll');
        $router->put('/orders/{order_id}', 'OrderController@updateOrder');
        $router->get('/orders/{order_id}', 'OrderController@getOrderById');
        $router->get(
            '/businesses/{business_id}/orders',
            'OrderController@getBusinessOrders'
        );
        $router->get(
            '/clients/{client_id}/orders',
            'OrderController@getClientOrders'
        );

        //PUSH NOTIFICATIONS
        $router->post('/push', 'OrderController@sendPush');

        //Working
        $router->post(
            '/distance',
            'OrderController@haversineGreatCircleDistance'
        );
        $router->post('/geocoder', 'OrderController@getCordinatesFromAddress');
        $router->post('/distancedriving', 'OrderController@GetDrivingDistance');
    }
);

Route::group(
    [
        'middleware' => 'api',
        'prefix' => 'v2',
    ],
    function ($router) {
       
        $router->get(
            '/addresses/{address_id}',
            'AddressController@getAddressById'
        );

        $router->get('/categories', 'CategoryController@getCategories');

        $router->get('/categories/test', 'CategoryController@getCategories');

        $router->get('/categories/test/all', 'CategoryController@getCategories');

               
    }
);
