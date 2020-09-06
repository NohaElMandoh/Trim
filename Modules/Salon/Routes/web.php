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
            Route::get('salons/{id}/password', 'SalonController@edit_password')->name('salons.password');
            Route::post('salons/{id}/password', 'SalonController@update_password');
            Route::resource('salons', 'SalonController');
        });
    }
);