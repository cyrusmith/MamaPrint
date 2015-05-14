<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 14.05.2015
 * Time: 11:46
 */

namespace mamaprint\infrastructure\events;


use Illuminate\Support\Facades\Facade;

class Events extends Facade {

    protected static function getFacadeAccessor() { return 'mamaprint\infrastructure\events\EventDispatcher'; }

}