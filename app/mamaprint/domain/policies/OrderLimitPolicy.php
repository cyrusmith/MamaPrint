<?php namespace mamaprint\domain\policies;

use mamaprint\SiteConfigProvider;

class OrderLimitPolicy
{

    public function __construct(
        OrderItemPricePolicy $orderItemPricePolicy,
        SiteConfigProvider $siteConfigProvider
    )
    {
        $this->orderItemPricePolicy = $orderItemPricePolicy;
        $this->siteConfigProvider = $siteConfigProvider;
    }

    public function meetsLowerLimit($order)
    {
        return $order->total >= $this->siteConfigProvider->getSiteConfig()->getMinOrderPrice() * 100;
    }

    public function canBuyInOneClick($user, $catalogItem)
    {
        $itemOrderPrice = $this->orderItemPricePolicy->catalogItemPriceForUser($user, $catalogItem);
        return $itemOrderPrice >= ($this->siteConfigProvider->getSiteConfig()->getMinOrderPrice() * 100);
    }

}