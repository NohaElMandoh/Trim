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

Route::prefix('offers')->group(function () {
    Route::get('/', 'ApiOfferController@index');
    Route::post('ids', 'ApiOfferController@ids');
    Route::post('create', 'ApiOfferController@create')->middleware('auth:api');
    Route::post('update', 'ApiOfferController@update')->middleware('auth:api');
    Route::post('delete', 'ApiOfferController@delete')->middleware('auth:api');
    Route::get('me', 'ApiOfferController@me')->middleware('auth:api');
});