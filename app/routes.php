<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function () {
    return View::make('main');
});

Route::get('/workbook', function () {
    return View::make('workbook');
});

Route::get('/login', function () {
    return View::make('auth.login');
});

Route::get('/register', function () {
    return View::make('auth.register');
});

Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');

Route::post('subscribe/getcards', 'SubscribeController@getCards');