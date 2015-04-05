<?php namespace Policy;

class OrderItemPricePolicy
{

    public function catalogItemPriceForUser($user, $catalogItem)
    {
        if ($user->isGuest()) {
            return $catalogItem->price;
        } else {
            return $catalogItem->registered_price;
        }
    }

}