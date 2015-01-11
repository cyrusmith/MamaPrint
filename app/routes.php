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

Route::get('/login', array('before' => 'guest', function () {
    return View::make('auth.login');
}));
Route::get('/logout', 'AuthController@logout');

Route::get('/register', array('before' => 'guest', function () {
    return View::make('auth.register');
}));

Route::get('/register/confirm', 'AuthController@confirm');
Route::get('/register/regcomplete', function () {
    return View::make('auth.regcomplete');
});

Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');
Route::post('/register_guest', 'AuthController@registerGuest');

Route::post('/subscribe/getcards', 'SubscribeController@getCards');

Route::post('/buyitem/{itemId}', array('before' => 'csrf', 'uses' => 'OrdersController@buyitem'));

Route::post('/api/v1/payments/onpay', 'PaymentsController@onpayApi');

Route::get('/pay/success/{orderId}', 'PaymentsController@paymentSuccess');

Route::get('/pay/fail', function () {
    return View::make('payments.fail');
});

Route::get('/pay/{orderId}', 'PaymentsController@pay');
Route::get('/orders/{orderId}/download', 'OrdersController@download')->where(['orderId' => '[0-9]+']);

Route::get('/user', array('before' => 'auth', 'uses' => 'UserController@downloads'));

Route::get('/about', function() {
    return View::make('statics.about');
});
Route::get('/public_offer', function() {
    return View::make('statics.public_offer');
});
Route::get('/howto', function() {
    return View::make('statics.howto');
});
Route::get('/contacts', function() {
    return View::make('statics.contacts');
});

//admin
Route::get('/admin/catalog', 'Admin\AdminCatalogController@index');
Route::get('/admin/catalog/add', 'Admin\AdminCatalogController@add');
Route::get('/admin/catalog/edit/{id}', 'Admin\AdminCatalogController@edit');
Route::post('/admin/catalog/save', 'Admin\AdminCatalogController@save');
