<?php

use Illuminate\Support\Facades\Route;

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
        Route::group(['prefix' => 'dashboard', 'namespace' => 'Dashboard', 'middleware' => ['auth', 'dashboard']], function () {
            Route::get('/', 'DashboardController@index')->name('dashboard');
            Route::post('send-notification', 'DashboardController@send_notification')->name('dashboard.send_notification');
            Route::resource('settings', 'SettingController');
            Route::resource('roles', 'RoleController');
            Route::get('users/{id}/password', 'UserController@edit_password')->name('users.password');
            Route::post('users/{id}/password', 'UserController@update_password');
            Route::resource('users', 'UserController');
            Route::resource('factories', 'FactoryController');
            Route::resource('delegates', 'DelegateController');
            Route::post('delegates/{id}/password', 'DelegateController@update_password');

            Route::get('delegates/{id}/password', 'DelegateController@edit_password')->name('delegates.password');
            Route::get('factories/{id}/password', 'FactoryController@edit_password')->name('factories.password');
            Route::post('factories/{id}/password', 'FactoryController@update_password');
            Route::resource('translators', 'TranslatorCotroller');
        });
        Route::any('/', 'HomeController@home')->name('home');
        Route::get('career', 'HomeController@career')->name('career');
        Route::post('career', 'HomeController@save_career');

        Route::post('logout', 'Auth\LoginController@logout')->name('logout');
        Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
        // Password Reset Routes...
        Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('login', 'Auth\LoginController@login');
        Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::post('password/reset', 'Auth\ResetPasswordController@reset');
    }

);
Route::get('files/{filename?}', 'HomeController@file_show')->name('file_show');
// Route::resource('subscriptions', '\Modules\Subscription\Http\Controllers\SubscriptionController');
