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
            App::abort(404, "Товар не найден");
        }

        if (!$item->canBuyInOneClick()) {
            App::abort(400, "Минимальная сумма покупки - " . \Illuminate\Support\Facades\App::make("SiteConfigProvider")->getSiteConfig()->getMinOrderPrice() . " Р.");
        }

        $user = App::make('UsersService')->getUser();

        if (empty($user)) {
            App::abort(500, 'Пользователь не задан. Войдите или зарегистрируйтесь.');
        }

        if (Auth::check()) {
            foreach ($user->catalogItems as $userCatalogItem) {
                if ($userCatalogItem->id == $item->id) {
                    App::abort(400, "Материал &laquo;" . $userCatalogItem->title . "&raquo; уже оплачен и доступен для скачивания в <a href='" . URL::to('/user') . "'>личном кабинете.");
                }
            }
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

        $userCatalogItemIds = [];
        foreach ($user->catalogItems as $userCatalogItem) {
            $userCatalogItemIds[] = $userCatalogItem->id;
        }

        if ($cartItems->isEmpty()) {
            App::abort(400, Lang::get('messages.error.cart_is_empty'));
        }

        $siteConfig = \Illuminate\Support\Facades\App::make("SiteConfigProvider")->getSiteConfig();

        try {

            DB::beginTransaction();
            $total = 0;

            $order = new Order;
            $order->user()->associate($user);
            $order->status = Order::STATUS_PENDING;

            $orderItems = [];
            foreach ($cart->items as $item) {
                if (Auth::check() && in_array($item->catalogItem->id, $userCatalogItemIds)) continue;
                $price = $item->catalogItem->getOrderPrice();
                $orderItem = new OrderItem;
                $orderItem->price = $price;
                $orderItem->catalogItem()->associate($item->catalogItem);
                $orderItems[] = $orderItem;
                $total += $price;
            }

            if ($total == 0 || $total < ($siteConfig->getMinOrderPrice() * 100)) {
                throw new Exception("Минимальная сумма заказа - " . $siteConfig->getMinOrderPrice() . " P.");
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
            App::abort(500, Lang::get('messages.error.could_not_create_order') . ": " . $e->getMessage());
        }

    }

    public function getOrderDownload($token)
    {
        DB::table('download_links')->whereRaw('TIMESTAMPDIFF(MINUTE, created_at, \'' . date('Y-m-d H:i:s') . '\') > ' . Config::get('mamaprint.download_link_timeout'))->delete();
        $link = DownloadLink::where('token', '=', $token)->first();
        if (empty($link)) {
            App::abort(404, 'Ссылка не существует');
        }
        $order = Order::find($link->order->id);
        Cookie::queue('download_token', $token, 2628000);
        return View::make('order.download', [
            'order' => $order,
            'token' => $token
        ]);
    }

    public function getOrderAttachment($token)
    {

        DB::table('download_links')->whereRaw('TIMESTAMPDIFF(MINUTE, created_at, \'' . date('Y-m-d H:i:s') . '\') > ' . Config::get('mamaprint.download_link_timeout'))->delete();

        $link = DownloadLink::where('token', '=', $token)->first();
        if (empty($link)) {
            App::abort(404, 'Ссылка не существует');
        }
        $order = Order::find($link->order->id);

        if (empty($order)) {
            App::abort(404, 'Заказ не найден.');
        }

        if (!$order->isComplete()) {
            App::abort(404, 'Заказ еще не оплачен.');
        }

        $file = App::make('OrderService')->createOrderArchive($order->id);

        if (file_exists($file)) {
            return Response::download($file, "Заказ №{$order->id} с сайта Mama-print.ru.zip");
        } else {
            Log::error($file . ' does not exists');
            App::abort(404, 'Нет материалов для скачивания');
        }

    }

    public function getOrderAttachmentForGuestUser($orderId)
    {

        $order = Order::find($orderId);

        if (empty($order)) {
            App::abort(404, 'Заказ не найден.');
        }

        $user = App::make('UsersService')->getUser();
        if (empty($user)) {
            App::abort(400, 'Ошибка приложения - пользователь не задан. Обновите страницу. Если ошибка повторяется, то сообщите нам на email ' . \Illuminate\Support\Facades\Config::get('mamaprint.supportemail'));
        }

        if ($order->user->id != $user->id) {
            App::abort(401, 'Доступ к скачиванию закрыт.');
        }

        if (!$order->isComplete()) {
            App::abort(404, 'Заказ еще не оплачен.');
        }

        $file = App::make('OrderService')->createOrderArchive($order->id);

        if (file_exists($file)) {
            return Response::download($file, "Заказ №{$order->id} с сайта Mama-print.ru.zip");
        } else {
            Log::error($file . ' does not exists');
            App::abort(404, 'Нет материалов для скачивания');
        }

    }

}