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
use \Illuminate\Support\Facades\Log;

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

    public function createOrder()
    {

        $user = App::make('UsersService')->getUser();
        if (empty($user)) {
            App::abort(400, Lang::get('messages.error.usernotfound'));
        }

        $cart = $user->cart;

        if (empty($cart)) {
            App::abort(400, Lang::get('messages.error.cart_is_empty'));
        }

        $cartItems = $cart->items;

        if ($cartItems->isEmpty()) {
            App::abort(400, Lang::get('messages.error.cart_is_empty'));
        }

        try {

            DB::beginTransaction();
            $total = 0;

            $order = new Order;
            $order->user()->associate($user);
            $order->status = Order::STATUS_PENDING;

            $orderItems = [];
            foreach ($cart->items as $item) {
                $price = $item->catalogItem->getOrderPrice();
                $orderItem = new OrderItem;
                $orderItem->price = $price;
                $orderItem->catalogItem()->associate($item->catalogItem);
                $orderItems[] = $orderItem;
                $total += $price;
            }
            $order->total = $total;
            $order->save();
            $order->items()->saveMany($orderItems);
            $cart->items()->delete();
            DB::commit();
            //TODO
            return Redirect::to("https://secure.onpay.ru/pay/mamaprint_ru?price_final=true&ticker=RUR&pay_mode=fix&price=" . ((float)($order->total / 100.0)) . "&pay_for=" . $order->id . "&user_email=" . (Auth::check() ? Auth::user()->email : '') . "&url_success=" . URL::to('/pay/success/' . $order->id) . "&url_fail=" . URL::to('/pay/fail') . "&ln=ru");
        } catch (Exception $e) {
            Log::error($e);
            DB::rollback();
            App::abort(500, Lang::get('messages.error.could_not_create_order'));
        }

    }

    public function download($orderId)
    {
        $user = App::make('UsersService')->getUser();

        if (empty($user)) {
            App::abort(404, 'Пользователь не найден. Авторизуйтесь на сайте.');
        }

        $order = $user->orders()->where('id', '=', $orderId)->first();

        if (empty($order)) {
            App::abort(404, 'Заказ не найден. Авторизуйтесь на сайте.');
        }

        if (!$order->isComplete()) {
            App::abort(404, 'Заказ еще не оплачен.');
        }

        $orderItem = $order->items()->first();
        $catalogItem = $orderItem->catalogItem;

        $file = base_path() . DIRECTORY_SEPARATOR . 'downloads' . DIRECTORY_SEPARATOR . $catalogItem->id . '.' . $catalogItem->asset_extension;

        if (file_exists($file)) {
            return Response::download($file, $catalogItem->asset_name . "." . $catalogItem->asset_extension);
        } else {
            Log::error($file . ' does not exists');
            App::abort(404);
        }

    }

}