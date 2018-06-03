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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/login', 'UserController@login');
Route::post('auth/register', 'UserController@register');

Route::group(['middleware' => 'auth.token'], function () {
    Route::post('auth/getUser', 'UserController@getUser');
    Route::post('menu/get', 'MenuController@get');
    Route::post('menu/getMenuAll', 'MenuController@getMenuAll');
    Route::post('menu/getMenu', 'MenuController@getMenu');
    Route::post('menu/create', 'MenuController@create');
    Route::post('menu/update', 'MenuController@update');
    Route::post('menu/delete', 'MenuController@delete');
});

