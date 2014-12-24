<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 23.12.2014
 * Time: 21:49
 */


namespace Order;

use Eloquent;

class OrderItem extends Eloquent
{
    protected $table = 'order_items';

    public function catalogItem()
    {
        return $this->belongsTo('Catalog\CatalogItem');
    }

    public function getPrice($value)
    {
        return (int)$value;
    }

}