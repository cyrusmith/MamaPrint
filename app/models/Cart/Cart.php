<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 19.01.2015
 * Time: 12:48
 */

namespace Cart;

use Eloquent;
use Cart\CartItem;

class Cart extends Eloquent
{

    protected $table = 'carts';

    public function items()
    {
        return $this->hasMany('Cart\CartItem');
    }

    public function addCartItem($catalogItem)
    {
        $existingItem = $this->items()->where('catalog_item_id', '=', $catalogItem->id)->first();
        if (!empty($existingItem)) {
            return $existingItem;
        }
        $cartItem = new CartItem;
        $cartItem->catalogItem()->associate($catalogItem);
        $this->items()->save($cartItem);
        return $cartItem;
    }

}