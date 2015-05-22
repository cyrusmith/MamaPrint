<?php namespace mamaprint\domain\policies;

use Illuminate\Support\Facades\App;

class OrderLimitPolicy
{

    public function meetsLowerLimit($order)
    {
        return $order->total >= App::make("SiteConfigProvider")->getSiteConfig()->getMinOrderPrice() * 100;
    }

}