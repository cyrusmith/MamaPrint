<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 05.04.2015
 * Time: 16:13
 */

namespace Policy;

use Illuminate\Support\Facades\App;

class OrderLimitPolicy
{

    public function meetsLowerLimit($order)
    {
        return $order->total >= App::make("SiteConfigProvider")->getSiteConfig()->getMinOrderPrice() * 100;
    }

}