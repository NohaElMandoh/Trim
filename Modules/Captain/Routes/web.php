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
            Route::get('captains/{id}/password', 'CaptainController@edit_password')->name('captains.password');
            Route::post('captains/{id}/password', 'CaptainController@update_password');
            Route::resource('captains', 'CaptainController');
        });
    }
);
