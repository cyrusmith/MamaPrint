<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

    app_path() . '/commands',
    app_path() . '/services',
    app_path() . '/controllers',
    app_path() . '/models',
    app_path() . '/database/seeds',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path() . '/logs/laravel.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

/*App::error(function (Exception $exception, $code) {
    Log::error($exception);
    if ($code == 400 || $code === 401 || $code === 500) {
        return Response::view('errors.' . $code, array('error' => $exception->getMessage()), $code);
    }
});*/

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function () {
    return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

App::singleton('UsersService', function ($app) {
    return new UsersService;
});

App::singleton('OrderService', function ($app) {
    return new OrderService;
});

App::singleton('AuthService', function ($app) {
    return new AuthService;
});

App::singleton('AttachmentService', function ($app) {
    return new AttachmentService();
});

App::singleton('CatalogService', function ($app) {
    return new CatalogService();
});

App::singleton('GalleryService', function ($app) {
    return new GalleryService();
});

App::singleton('SiteConfigProvider', function ($app) {
    return new SiteConfigProvider();
});

Blade::extend(function ($value) {
    return preg_replace('/\@define(.+)/', '<?php ${1}; ?>', $value);
});

App::missing(function ($exception) {
    return Response::view('errors.404', array('error' => $exception->getMessage()), 404);
});

View::composer('*', function ($view) {

    $user = App::make("UsersService")->getUser();
    $cartItems = [];
    $cartIds = [];
    $userCatalogItemIds = [];
    if (!empty($user)) {
        $cart = $user->getOrCreateCart();
        foreach ($cart->items as $item) {
            $catalogItem = $item->catalogItem;
            $cartIds[] = $catalogItem->id;
            $cartItems[] = [
                'id' => $catalogItem->id . "",
                'title' => $catalogItem->title,
                'price' => $catalogItem->getOrderPrice()
            ];
        }

        if (Auth::check()) {
            foreach ($user->catalogItems as $item) {
                $userCatalogItemIds[] = $item->id;
            }
        }
    }
    $view->with('cart', $cartItems);
    $view->with('cart_ids', $cartIds);
    $view->with('user_item_ids', $userCatalogItemIds);
    $view->with('user', $user);
    $view->with('site_config', \Illuminate\Support\Facades\App::make("SiteConfigProvider")->getSiteConfig());

    if (Session::has('form')) {
        $form = Session::get('form');
        foreach ($form as $name => $value) {
            $view->with($name, $value);
        }
    }


});

require app_path() . '/filters.php';
require app_path().'/helpers.php';