<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 23.12.2014
 * Time: 21:12
 */

use Order\Order;
use Order\OrderItem;
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

        if (empty($user)) {
            App::abort(500, 'user not set');
        }

        $order = new Order;

        DB::transaction(function () use ($item, $user, &$order) {

            $order->total = $item->price;
            $order->user()->associate($user);
            $order->save();

            $orderItem = new OrderItem;
            $orderItem->price = $item->price;
            $orderItem->catalogItem()->associate($item);
            $order->items()->save($orderItem);
            $order->save();
        });

        if ($order->id) {
            return Redirect::to('/pay/' . $order->id);
        }

        App::abort(500, 'Could not create order');
    }


}