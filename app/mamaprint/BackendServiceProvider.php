<?php

namespace mamaprint;

use Illuminate\Support\ServiceProvider;

class BackendServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('mamaprint\domain\order\OrderRepositoryInterface', 'mamaprint\domain\order\OrderRepository');
        $this->app->bind('mamaprint\domain\user\UserRepositoryInterface', 'mamaprint\domain\user\UserRepository');
        $this->app->bind('mamaprint\domain\catalog\CatalogRepositoryInterface', 'mamaprint\domain\catalog\CatalogRepository');

        $this->app->bind('UserService', 'mamaprint\application\services\UserService');
        $this->app->bind('OrderService', 'mamaprint\application\services\OrderService');

        $this->app->bind('OrderItemPricePolicy', 'mamaprint\domain\policies\OrderItemPricePolicy');
        $this->app->bind('OrderLimitPolicy', 'mamaprint\domain\policies\OrderLimitPolicy');

        $this->app->singleton('mamaprint\SiteConfigProvider', function() {
            return new SiteConfigProviderImpl();
        });

        $this->app->bind('SiteViewComposer', 'mamaprint\view\SiteViewComposer');

        $this->app->bind('mamaprint\infrastructure\events\EventDispatcher', 'mamaprint\infrastructure\events\LaravelEventDispatcher');
    }
}