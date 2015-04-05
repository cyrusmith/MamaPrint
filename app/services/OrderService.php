<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 25.12.2014
 * Time: 12:14
 */
use \Illuminate\Support\Facades\DB;
use \Order\Order;
use \Order\OrderItem;

class OrderService
{

    /**
     * Creates order from cart items
     */
    public function createOrderFromCart($user)
    {
        try {
            DB::beginTransaction();

            $cart = $user->getOrCreateCart();
            if ($cart->items->isEmpty()) {
                throw new Exception("Невозможно создать заказ т.к. корзина пуста");
            }

            $itemPricePolicy = App::make("\Policy\OrderItemPricePolicy");
            $orderLimitPolicy = App::make("\Policy\OrderLimitPolicy");

            $total = 0;

            $order = new Order;
            $order->user()->associate($user);
            $order->status = Order::STATUS_PENDING;

            $orderItems = [];
            foreach ($cart->items as $item) {
                if ($user->hasItem($item->catalogItem)) continue;
                $priceForUser = $itemPricePolicy->catalogItemPriceForUser($user, $item->catalogItem);
                if ($priceForUser > 0) {
                    $orderItem = new OrderItem;
                    $orderItem->price = $priceForUser;
                    $orderItem->catalogItem()->associate($item->catalogItem);
                    $orderItems[] = $orderItem;
                    $total += $priceForUser;
                }
            }

            $order->total = $total;

            if (!$orderLimitPolicy->meetsLowerLimit($order)) {
                throw new Exception("Минимальная сумма заказа - " . App::make("SiteConfigProvider")->getSiteConfig()->getMinOrderPrice() . " P.");
            }

            $order->save();
            $order->items()->saveMany($orderItems);

            DB::commit();
            return $order;
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function completeOrder($orderId)
    {
        DB::beginTransaction();
        try {

            $order = Order::find($orderId);

            if (empty($order)) {
                throw new InvalidArgumentException("Order #$orderId not found");
            }

            if ($order->status !== Order::STATUS_PENDING) {
                throw new InvalidArgumentException("Order #$orderId already payed");
            }

            $user = User::find($order->user->id);
            $cart = $user->cart;
            $cart->items()->delete();
            $order->status = Order::STATUS_COMPLETE;
            $order->save();

            $userCatalogItemIds = [];
            foreach ($user->catalogItems as $userCatalogItem) {
                $userCatalogItemIds[] = $userCatalogItem->id;
            }

            $catalogItems = [];
            foreach ($order->items as $item) {
                if (!in_array($item->catalogItem->id, $userCatalogItemIds)) {
                    $catalogItems[] = $item->catalogItem;
                }
            }

            $user->attachCatalogItems($catalogItems);

            DB::commit();
            return $order;

        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            throw $e;
        }
    }

    public function createOrderArchive($orderId)
    {
        $order = Order::find($orderId);

        if (empty($order) || Order::STATUS_COMPLETE != $order->status) {
            throw new Exception("Заказ не существует или еще не оплачен");
        }

        $DS = DIRECTORY_SEPARATOR;
        $path = Config::get('mamaprint.tmp_orders_path');
        $file = $path . $DS . $order->id . ".zip";

        if (!file_exists($file)) {

            if (!file_exists($path)) {
                if (mkdir($path, 0777, true) !== true) {
                    throw new Exception(Lang::get('messages.error.could_not_create_folder', [
                        'path' => $path
                    ]));
                }
            }

            $zip = new ZipArchive();
            $zip->open($file, ZipArchive::CREATE);

            $attachmentsService = App::make('AttachmentService');

            foreach ($order->items as $item) {
                $n = 1;
                $catalogItem = $item->catalogItem;
                $attachments = $catalogItem->attachments;
                foreach ($attachments as $attachment) {
                    $attachmentPath = $attachmentsService->getFilePath($attachment->id);
                    if ($attachmentPath) {
                        $zip->addFile($attachmentPath, $catalogItem->slug . '/' . $n . '.' . $attachment->extension);
                    }
                    $n++;
                }
            }

            $zip->close();
        }

        return $file;

    }

}
