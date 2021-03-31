<?php

use Illuminate\Http\Request;

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

Route::group(['middleware' => 'auth:api', 'prefix' => 'orders'], function () {
    Route::post('financials', 'ApiOrderController@financials');
    Route::post('messages', 'ApiOrderController@getMessages');
    Route::post('messages/send', 'ApiOrderController@sendMessage');
    Route::group(['prefix' => 'user'], function () {
        Route::get('current', 'ApiOrderController@currentUserOrders');
        Route::get('cancelled', 'ApiOrderController@cancelledUserOrders');
        Route::get('delivered', 'ApiOrderController@deliveredUserOrders');
    });
    Route::group(['prefix' => 'shop'], function () {
        Route::get('current', 'ApiOrderShopController@currentShopOrders');
        Route::get('cancelled', 'ApiOrderShopController@cancelledShopOrders');
        Route::get('delivered', 'ApiOrderShopController@deliveredShopOrders');
    });
    Route::group(['prefix' => 'oq'], function () {
        Route::post('store', 'ApiOqController@store');
    });
    // Route::group(['prefix' => 'anywhere'], function () {
    //     Route::post('store', 'ApiAnyWhereController@store');
    // });
    Route::group(['prefix' => 'week'], function () {
        Route::post('store', 'ApiWeekController@store');
    });
    Route::group(['prefix' => 'oneway'], function () {
        Route::post('store', 'ApiOneWayController@store');
    });
    Route::group(['prefix' => 'moreway'], function () {
        Route::post('store', 'ApiMoreWayController@store');
    });
});