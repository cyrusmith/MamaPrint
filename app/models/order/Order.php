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

    const STATUS_CART = 'cart';
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETE = 'complete';

    protected $table = 'orders';

    public function items()
    {
        return $this->hasMany('Order\OrderItem');
    }

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function getTotalAttribute($value)
    {
        return intval($value);
    }

}