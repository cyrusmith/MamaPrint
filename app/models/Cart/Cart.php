<?php namespace Cart;


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

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($cart) {
            $cart->items()->delete();
        });
    }


}