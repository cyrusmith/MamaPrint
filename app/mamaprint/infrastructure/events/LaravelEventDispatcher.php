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
        Event::fire($event->getKey(), array($event));
    }

    function listen(AppEvent $event, $listener)
    {
        if (!is_string($listener)) throw new \InvalidArgumentException("Listener directive should be string");
        Event::listen($event->getKey(), $listener);
    }
}