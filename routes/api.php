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

Route::group([ 'namespace' => 'Api' ], function () {
    Route::post('login', 'UserController@login');
    Route::post('register', 'UserController@register');
    Route::get('logout', 'UserController@logout')->middleware('auth:api');
    Route::post('user/reset-password', 'UserController@sendResetLinkEmail');
    Route::post('user/new-password', 'UserController@newPassword');
    Route::post('user/change-password', 'UserController@changePassword')->middleware('auth:api');
    Route::get('user/info', 'UserController@info')->middleware('auth:api');
    Route::post('user/token', 'UserController@add_token');
    Route::get('user/notifications', 'UserController@get_notifications')->middleware('auth:api');
    Route::post('user/notifications/read', 'UserController@read_notification')->middleware('auth:api');
    Route::post('user/update-image', 'UserController@update_image')->middleware('auth:api');
    Route::post('user/update-info', 'UserController@update_info')->middleware('auth:api');
    Route::post('user/activate', 'UserController@activate');

    // Setings
    Route::get('settings', 'SettingController@index');
});