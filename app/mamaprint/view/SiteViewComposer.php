<?php

namespace mamaprint\view;

use mamaprint\application\services\UserService;
use mamaprint\domain\policies\OrderItemPricePolicy;
use mamaprint\domain\policies\OrderLimitPolicy;
use mamaprint\SiteConfigProvider;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 04.06.2015
 * Time: 22:14
 */
class SiteViewComposer
{

    public function __construct(
        UserService $userService,
        SiteConfigProvider $siteConfigProvider,
        OrderLimitPolicy $orderLimitPolicy
    )
    {
        $this->userService = $userService;
        $this->siteConfigProvider = $siteConfigProvider;
        $this->orderLimitPolicy = $orderLimitPolicy;
    }

    public function compose($view)
    {
        $user = $this->userService->getUser();
        $cartItems = [];
        $cartIds = [];
        $userCatalogItemIds = [];
        if (!empty($user)) {
            $cart = $user->getOrCreateCart();
            foreach ($cart->items as $item) {
                $catalogItem = $item->catalogItem;
                $cartIds[] = $catalogItem->id;
                $cartItems[] = [
                    'id' => $catalogItem->id . "",
                    'title' => $catalogItem->title,
                    'price' => $catalogItem->getOrderPrice()
                ];
            }

            if (Auth::check()) {
                foreach ($user->catalogItems as $item) {
                    $userCatalogItemIds[] = $item->id;
                }
            }
        }
        $view->with('cart', $cartItems);
        $view->with('cart_ids', $cartIds);
        $view->with('user_item_ids', $userCatalogItemIds);
        $view->with('user', $user);
        $view->with('site_config', $this->siteConfigProvider->getSiteConfig());
        $view->with('orderLimitPolicy', $this->orderLimitPolicy);

        if (Session::has('form')) {
            $form = Session::get('form');
            foreach ($form as $name => $value) {
                $view->with($name, $value);
            }
        }
    }

}