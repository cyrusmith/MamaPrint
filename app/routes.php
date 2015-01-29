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

Route::group(array('before' => 'guest_create'), function () {

    Route::get('/', 'CatalogController@index');
    Route::get('/catalog/{path}', 'CatalogController@item')->where('path', '(.*)');

    Route::get('/about', function () {
        return View::make('statics.about');
    });

    Route::get('/public_offer', function () {
        return View::make('statics.public_offer');
    });

    Route::get('/howto', function () {
        return View::make('statics.howto');
    });

    Route::get('/contacts', function () {
        return View::make('statics.contacts');
    });

    Route::get('/cart', 'CartController@userCart');

});

Route::group(array('before' => 'auth'), function () {

    Route::get('/user', 'UserController@viewCatalogItems');
    Route::get('/user/settings', function () {
        return View::make('user.settings');
    });
    Route::get('/logout', 'AuthController@logout');
    Route::post('/user/settings/name', array('before' => 'csrf', 'uses' => 'UserController@saveName'));
    Route::post('/user/settings/password', array('before' => 'csrf', 'uses' => 'UserController@savePassword'));

});

Route::group(array('before' => 'guest'), function () {

    Route::get('/login', function () {
        return View::make('auth.login');
    });

    Route::get('/register', function () {
        return View::make('auth.register');
    });

    Route::get('/register/confirm', 'AuthController@confirm');

    Route::get('/register/regcomplete', function () {
        return View::make('auth.regcomplete');
    });

    Route::post('/login', 'AuthController@login');
    Route::post('/register', 'AuthController@register');

    Route::get('/remindpassword/{token}', 'RemindersController@getReset');
    Route::post('/remindpassword/{token}', 'RemindersController@postReset');

    Route::get('/remindpassword', 'RemindersController@getRemind');
    Route::post('/remindpassword', 'RemindersController@postRemind');

});

Route::group(array('before' => 'admin'), function () {

    Route::get('/admin/catalog', 'Admin\AdminCatalogController@index');
    Route::get('/admin/catalog/add', 'Admin\AdminCatalogController@add');
    Route::get('/admin/catalog/edit/{id}', 'Admin\AdminCatalogController@edit');
    Route::post('/admin/catalog/save', 'Admin\AdminCatalogController@save');

    Route::get('/admin/api/v1/attachments/{id}', 'Admin\AdminAttachmentController@view');
    Route::put('/admin/api/v1/attachments/{id}', 'Admin\AdminAttachmentController@update');
    Route::delete('/admin/api/v1/attachments/{id}', 'Admin\AdminAttachmentController@delete');

    Route::get('/admin/attachments/{id}/download', 'Admin\AdminAttachmentController@download');

    Route::delete('/admin/gallery/{id}', 'GalleryController@deleteImage');

    Route::get('/admin/settings', 'Admin\AdminSettingsController@edit');
    Route::post('/admin/settings', 'Admin\AdminSettingsController@edit');

});

Route::post('/buyitem/{itemId}', array('before' => 'csrf', 'uses' => 'OrdersController@buyitem'));
Route::post('/createorder/', array('before' => 'csrf', 'uses' => 'OrdersController@createOrder'));

Route::post('/subscribe/getcards', 'SubscribeController@getCards');

Route::get('/pay/success/{orderId}', 'PaymentsController@paymentSuccess');

Route::get('/pay/fail', function () {
    return View::make('payments.fail');
});

Route::get('/pay/{orderId}', 'PaymentsController@pay');
Route::get('/orders/{orderId}/download', 'OrdersController@download')->where(['orderId' => '[0-9]+']);

Route::post('/api/v1/payments/onpay', 'PaymentsController@onpayApi');
Route::post('/api/v1/cart', 'CartController@addItem');
Route::get('/api/v1/cart', 'CartController@items');
Route::get('/api/v1/cart/{itemId}', 'CartController@viewItem');
Route::delete('/api/v1/cart/{itemId}', 'CartController@deleteItem');

Route::get('/images/{id}', 'GalleryController@view');

Route::group(array('before' => 'test'), function () {

    Route::get('/test', function () {
        return View::make('test.index');
    });

    Route::post('/test/orders/payorder', 'Test\OrdersTestController@testPayOrder');

});

Route::when('*', 'register_globals');