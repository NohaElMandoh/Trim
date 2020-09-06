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
            Route::get('oq_orders/{id}/messages', 'OqController@messages')->name('oq_orders.messages');
            Route::get('oq_orders/{id}/status', 'OqController@status')->name('oq_orders.status');
            Route::post('oq_orders/{id}/status', 'OqController@post_status');
            Route::resource('oq_orders', 'OqController');

            Route::get('anywhere_orders/{id}/messages', 'AnyWhereController@messages')->name('anywhere_orders.messages');
            Route::get('anywhere_orders/{id}/status', 'AnyWhereController@status')->name('anywhere_orders.status');
            Route::post('anywhere_orders/{id}/status', 'AnyWhereController@post_status');
            Route::resource('anywhere_orders', 'AnyWhereController');

            Route::get('week_orders/{id}/messages', 'WeekController@messages')->name('week_orders.messages');
            Route::get('week_orders/{id}/status', 'WeekController@status')->name('week_orders.status');
            Route::post('week_orders/{id}/status', 'WeekController@post_status');
            Route::resource('week_orders', 'WeekController');

            Route::get('oneway_orders/{id}/messages', 'OneWayController@messages')->name('oneway_orders.messages');
            Route::get('oneway_orders/{id}/status', 'OneWayController@status')->name('oneway_orders.status');
            Route::post('oneway_orders/{id}/status', 'OneWayController@post_status');
            Route::resource('oneway_orders', 'OneWayController');

            Route::get('moreway_orders/{id}/messages', 'MoreWayController@messages')->name('moreway_orders.messages');
            Route::get('moreway_orders/{id}/status', 'MoreWayController@status')->name('moreway_orders.status');
            Route::post('moreway_orders/{id}/status', 'MoreWayController@post_status');
            Route::resource('moreway_orders', 'MoreWayController');
        });
    }
);
