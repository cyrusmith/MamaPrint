<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 14.05.2015
 * Time: 19:50
 */

use \mamaprint\infrastructure\events\Events;

Events::listen("OrderCompleteEvent", "UserService@clearCart");