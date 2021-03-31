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
    ///login
    Route::post('login', 'UserController@login');
    Route::post('getVerificationCode', 'UserController@getVerificationCode');


    //register
    Route::post('register', 'UserController@register');
    Route::post('socialRegister', 'UserController@socialRegister');
    //reset password
    Route::post('user/reset-password', 'UserController@sendResetLinkEmail');
    Route::post('user/new-password', 'UserController@newPassword');
    Route::post('user/change-password', 'UserController@changePassword')->middleware('auth:api');
    ///token and notifications
    Route::post('user/token', 'UserController@add_token');
    Route::get('user/notifications', 'UserController@get_notifications')->middleware('auth:api');
    Route::post('user/notifications/read', 'UserController@read_notification')->middleware('auth:api');

    Route::post('user/activate', 'UserController@activate');

    Route::group(['middleware' => 'auth:api'], function () {

        Route::post('gender', 'UserController@gender');
        Route::get('logout', 'UserController@logout');
        // main lists
        Route::get('mainLists', 'MainController@mainLists');

        ////salon apis
        Route::post('allSalons', 'SalonController@allSalons');
        Route::post('rateSalon', 'SalonController@rateSalon');
        Route::post('avaliableDates', 'SalonController@avaliableDates');

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
        ///////cart
        Route::post('add-item-to-cart', 'CartController@addItemToCart');
        Route::get('get-cart-items', 'CartController@cartItems');
        Route::post('update-cart-item', 'CartController@updateCartItem');
        Route::post('delete-Cart-Item', 'CartController@deleteCartItem');
        Route::post('delete-all-cart-items','CartController@deleteAllCartItems');
        ///product
        Route::post('allCategories', 'ProductsController@allCategories');
        Route::post('products', 'ProductsController@products');
    });
    // Setings
    Route::get('settings', 'SettingController@index');
});
