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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});




Route::group(['prefix' => '/v1'], function () {
    // Routes which require auth
    Route::group([
        "middleware" => ['jwt.auth'],
    ], function () {
        //Route::get('nearby-foods', 'FoodController@getNearby');
        Route::get('foods/{food}/comments', 'FoodController@getComments');
        //Resources
        Route::resource('foods', 'FoodController',['only' => ['index', 'show', 'store', 'update', 'destroy']]);
        Route::resource('comments', 'CommentController', ['only' => ['index', 'show', 'store', 'destroy']]);
    });

    //Routes which does not require auth
    Route::post('auth', 'AuthenticateController@auth');
    Route::get('auth/refresh', 'AuthenticateController@refresh');
    Route::get('me', 'AuthenticateController@getAuthenticatedUser');
});