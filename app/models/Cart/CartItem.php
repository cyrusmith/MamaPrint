<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 19.01.2015
 * Time: 12:50
 */

namespace Cart;

use Eloquent;

class CartItem extends Eloquent
{
    protected $table = 'cart_items';
}