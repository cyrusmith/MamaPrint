<?php

namespace mamaprint\infrastructure\events;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 14.05.2015
 * Time: 11:36
 */
class LaravelEventDispatcher implements EventDispatcher
{
    function fire(AppEvent $event)
    {
        $parts = explode('\\', trim(get_class($event), '\\'));
        Event::fire($parts[count($parts) - 1], array($event));
    }

    function listen($eventClassName, $listener)
    {
        if (!is_string($listener)) throw new \InvalidArgumentException("Listener directive should be string");
        Event::listen($eventClassName, $listener);
    }
}