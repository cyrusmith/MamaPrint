<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 23.12.2014
 * Time: 21:12
 */

use Order\Order;
use Catalog\CatalogItem;

class OrdersController extends BaseController
{

    public function buyitem($itemId)
    {

        $item = CatalogItem::find($itemId);
        if (empty($item)) {
            return Response::view('errors.404', array(
                "error" => "Товар не найден"
            ), 404);
        }

        $user = App::make('UsersService')->getUser();

        DB::transaction(function () use ($item, $user) {

            $order = new Order;
            $order->total = $item->price;
            $order->user()->assign($user);
            $order->save();

            $orderItem = new OrderItem;
            $orderItem->title = $item->title;
            $orderItem->price = $item->price;
            $order->items()->save($orderItem);
        });
        return $itemId;
    }



}