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

            $itemPrice = $item->getOrderPrice();

            $order->total = $itemPrice;
            $order->status = Order::STATUS_PENDING;
            $order->user()->associate($user);
            $order->save();

            $orderItem = new OrderItem;
            $orderItem->price = $itemPrice;
            $orderItem->catalogItem()->associate($item);
            $order->items()->save($orderItem);
            $order->save();
        });

        if ($order->id) {
            return Redirect::to('/pay/' . $order->id);
        }

        App::abort(500, 'Could not create order');
    }

    public function download($orderId)
    {
        $user = App::make('UsersService')->getUser();

        if (empty($user)) {
            App::abort(400);
        }

        $order = $user->orders()->where('id', '=', $orderId)->first();

        if (empty($order)) {
            App::abort(404);
        }

        if (!$order->isComplete()) {
            App::abort(404);
        }

        $orderItem = $order->items()->first();
        $catalogItem = $orderItem->catalogItem;

        $file = base_path() . DIRECTORY_SEPARATOR . 'downloads' . DIRECTORY_SEPARATOR . $catalogItem->id . '.' . $catalogItem->asset_extension;

        if (file_exists($file)) {
            return Response::download($file, $catalogItem->asset_name.".".$catalogItem->asset_extension);
        } else {
            Log::error($file . ' does not exists');
            App::abort(404);
        }

    }

}