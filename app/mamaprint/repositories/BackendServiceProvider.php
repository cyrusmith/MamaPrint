<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 23.04.2015
 * Time: 9:58
 */

namespace mamaprint\repositories;

use Illuminate\Support\ServiceProvider;

class BackendServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('mamaprint\repositories\OrderRepositoryInterface', 'mamaprint\repositories\OrderRepository');
    }
}