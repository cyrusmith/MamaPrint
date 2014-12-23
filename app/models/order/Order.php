<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 23.12.2014
 * Time: 21:25
 */

namespace Order;

use Eloquent;

class Order extends Eloquent
{

    protected $table = 'orders';

    public function items()
    {
        return $this->hasMany('OrderItem');
    }

}