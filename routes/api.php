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


Route::group(['namespace' => 'Api'], function () {
    Route::group(['prefix' => 'client','namespace' => 'client'], function () {
        ///login
        Route::post('login', 'UserController@login');
        Route::post('getVerificationCode', 'UserController@getVerificationCode');


        //register
        Route::post('register', 'UserController@register');
        Route::post('socialRegister', 'UserController@socialRegister');
        //reset password

        Route::post('resetPassword', 'UserController@resetPassword');

        Route::post('user/reset-password', 'UserController@sendResetLinkEmail');
        Route::post('user/new-password', 'UserController@newPassword');

        ///token and notifications
        Route::post('user/token', 'UserController@add_token');
        Route::get('user/notifications', 'UserController@get_notifications')->middleware('auth:api');
        Route::post('user/notifications/read', 'UserController@read_notification')->middleware('auth:api');



        Route::group(['middleware' => 'auth:api'], function () {

            Route::post('gender', 'UserController@gender');
            Route::get('logout', 'UserController@logout');
            Route::post('user/change-password', 'UserController@changePassword');
            Route::post('user/activate', 'UserController@activate');
            // main lists
            Route::get('mainLists', 'MainController@mainLists');

            ////salon apis
            Route::post('allSalons', 'SalonController@allSalons');
            Route::post('rateSalon', 'SalonController@rateSalon');
            Route::post('avaliableDates', 'SalonController@avaliableDates');
            Route::post('addToFavorities', 'SalonController@addToFavorities');
            Route::post('nearestSalons', 'SalonController@nearestSalons');

            
            ////persons apis
            Route::post('allPersons', 'SalonController@allPersons');
            ///salon details
            Route::post('salon', 'SalonController@salon');
            //all governorates
            Route::get('governorates', 'SalonController@governorates');
            //all cities
            Route::get('cities', 'SalonController@cities');
            //update user info
            Route::get('user/info', 'UserController@info');
            Route::post('user/update-image', 'UserController@update_image');
            Route::post('user/update-info', 'UserController@update_info');
            Route::post('user/profile', 'UserController@profile');
            ///////cart
            Route::post('add-item-to-cart', 'CartController@addItemToCart');
            Route::get('get-cart-items', 'CartController@cartItems');
            Route::post('update-cart-item', 'CartController@updateCartItem');
            Route::post('delete-Cart-Item', 'CartController@deleteCartItem');
            Route::post('delete-all-cart-items', 'CartController@deleteAllCartItems');
            /////////////order apis

            // ---------add service order
            Route::post('newOrderWithService', 'OrderController@newOrderWithService');
            Route::post('newOrderWithOffer', 'OrderController@newOrderWithOffer');
            Route::post('newOrderWithProduct', 'OrderController@newOrderWithProduct');


            Route::post('updateOrder', 'OrderController@updateOrder');
            Route::get('myOrders', 'OrderController@myOrders');
            Route::post('order', 'OrderController@order');
            Route::post('approveOrder', 'OrderController@approveOrder');
            Route::post('cancelOrder', 'OrderController@cancelOrder');
            Route::post('confirmOrder', 'OrderController@confirmOrder');
            Route::post('updateOrderPaymentMethod', 'OrderController@updateOrderPaymentMethod');
            Route::post('rateOrder', 'OrderController@rateOrder');
            
            // --------get coupone details
            Route::post('getCoupone', 'OrderController@getCoupone');

            ///product
            Route::post('allCategories', 'ProductsController@allCategories');
            Route::post('products', 'ProductsController@products');

            ///coupones
            Route::get('allCoupones', 'CouponController@allCoupones');
            Route::post('coupone', 'CouponController@coupone');
            Route::post('winCoupone', 'CouponController@winCoupone');
            Route::get('myCoupons', 'CouponController@myCoupons');

            // --------offers------
             ///salon details
             Route::post('offer', 'OfferController@offer');
            //  ------------------
            Route::get('myFav_salon', 'MainController@myFav_salon');
            Route::get('myFav_person', 'MainController@myFav_person');

            
            

        });
        Route::post('sms', 'UserController@sms');
      
       
    });
    Route::group(['prefix' => 'salon','namespace' => 'salon'], function () {
        ///login
        Route::post('login', 'UserController@login');
        Route::post('getVerificationCode', 'UserController@getVerificationCode');
        //register
        Route::post('register', 'UserController@register');
        //reset password

        Route::post('resetPassword', 'UserController@resetPassword');

        Route::post('user/reset-password', 'UserController@sendResetLinkEmail');
        Route::post('user/new-password', 'UserController@newPassword');




        Route::group(['middleware' => 'auth:api'], function () {

            Route::get('logout', 'UserController@logout');
            Route::post('user/change-password', 'UserController@changePassword');
            Route::post('user/activate', 'UserController@activate');
           
            Route::post('user/profile', 'UserController@profile');

            // ---add work days---
            Route::post('work_days', 'UserController@work_days');

            
        });
        Route::post('sms', 'UserController@sms');
      
       
    });
      // Setings
    Route::get('contacts', 'SettingController@contacts');
    Route::get('settings', 'SettingController@index');
 
});
