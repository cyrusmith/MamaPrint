<?php namespace mamaprint\domain\policies;

use mamaprint\SiteConfigProvider;

class OrderLimitPolicy
{

    public function __construct(
        SiteConfigProvider $siteConfigProvider
    )
    {
        $this->siteConfigProvider = $siteConfigProvider;
    }

    public function meetsLowerLimit($order)
    {
        return $order->total >= $this->siteConfigProvider->getSiteConfig()->getMinOrderPrice() * 100;
    }

}