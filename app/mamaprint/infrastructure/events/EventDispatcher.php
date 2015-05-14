<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 14.05.2015
 * Time: 11:38
 */

namespace mamaprint\infrastructure\events;


interface EventDispatcher {

    function fire(AppEvent $event);
    function listen($eventClassName, $listener);

}