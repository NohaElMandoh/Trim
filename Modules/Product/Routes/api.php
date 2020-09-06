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

Route::prefix('products')->group(function () {
    Route::get('/', 'ApiProductController@index');
    Route::post('ids', 'ApiProductController@ids');
    Route::post('create', 'ApiProductController@create')->middleware('auth:api');
    Route::post('update', 'ApiProductController@update')->middleware('auth:api');
    Route::post('delete', 'ApiProductController@delete')->middleware('auth:api');
    Route::get('me', 'ApiProductController@me')->middleware('auth:api');
});
