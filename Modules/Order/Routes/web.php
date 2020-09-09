<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {
        Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', 'dashboard']], function () {


            Route::get('salon_orders/{id}/messages', 'OqController@messages')->name('salon_orders.messages');
            Route::get('salon_orders/{id}/status', 'OqController@status')->name('salon_orders.status');
            Route::post('salon_orders/{id}/status', 'OqController@post_status');
            Route::resource('salon_orders', 'SalonOrderController');

            Route::get('captain_orders/{id}/messages', 'CaptainOrderController@messages')->name('captain_orders.messages');
            Route::get('captain_orders/{id}/status', 'CaptainOrderController@status')->name('captain_orders.status');
            Route::post('captain_orders/{id}/status', 'CaptainOrderController@post_status');
            Route::resource('captain_orders', 'CaptainOrderController');

            Route::get('children_orders/{id}/messages', 'ChildrenOrderController@messages')->name('children_orders.messages');
            Route::get('children_orders/{id}/status', 'ChildrenOrderController@status')->name('children_orders.status');
            Route::post('children_orders/{id}/status', 'ChildrenOrderController@post_status');
            Route::resource('children_orders', 'ChildrenOrderController');

            Route::get('product_orders/{id}/messages', 'ProductOrderController@messages')->name('product_orders.messages');
            Route::get('product_orders/{id}/status', 'ProductOrderController@status')->name('product_orders.status');
            Route::post('product_orders/{id}/status', 'ProductOrderController@post_status');
            Route::resource('product_orders', 'ProductOrderController');
        });
    }
);
