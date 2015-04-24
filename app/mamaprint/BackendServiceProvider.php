<?php

namespace mamaprint;

use Illuminate\Support\ServiceProvider;

class BackendServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('mamaprint\domain\order\OrderRepositoryInterface', 'mamaprint\domain\order\OrderRepository');
        $this->app->bind('mamaprint\domain\user\UserRepositoryInterface', 'mamaprint\domain\user\UserRepository');
    }
}