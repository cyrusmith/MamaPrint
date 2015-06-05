<?php

namespace mamaprint\application\services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use mamaprint\application\exceptions\IllegalStateException;
use mamaprint\domain\catalog\CatalogRepositoryInterface;
use mamaprint\domain\order\OrderCompleteEvent;
use mamaprint\domain\order\OrderRepositoryInterface;
use mamaprint\domain\policies\OrderItemPricePolicy;
use mamaprint\domain\policies\OrderLimitPolicy;
use mamaprint\domain\user\UserRepositoryInterface;
use mamaprint\infrastructure\events\Events;
use mamaprint\SiteConfigProvider;
use Order\Order;
use Order\OrderItem;

class OrderService
{

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        CatalogRepositoryInterface $catalogRepository,
        UserRepositoryInterface $userRepository,
        OrderItemPricePolicy $orderItemPricePolicy,
        OrderLimitPolicy $orderLimitPolicy,
        SiteConfigProvider $siteConfigProvider
    )
    {
        $this->orderRepository = $orderRepository;
        $this->catalogRepository = $catalogRepository;
        $this->userRepository = $userRepository;

        $this->orderItemPricePolicy = $orderItemPricePolicy;
        $this->orderLimitPolicy = $orderLimitPolicy;

        $this->siteConfigProvider = $siteConfigProvider;
    }

    /**
     * @param $catalogItemId
     * @return mixed
     * @throws IllegalStateException
     * @throws NotFoundException
     * @throws InvalidArgumentException
     */
    public function buyItem($catalogItemId)
    {

        try {
            DB::beginTransacton();

            $catalogItem = $this->catalogRepository->find($catalogItemId);
            if (empty($catalogItem)) {
                throw new NotFoundException("Товар не найден");
            }

            if (!$this->orderLimitPolicy->canBuyInOneClick($this->userService->getUser(), $catalogItem)) {
                throw new \InvalidArgumentException("Минимальная сумма покупки - " . $this->siteConfigProvider->getSiteConfig()->getMinOrderPrice() . " Р.");
            }

            $user = $this->userService->getUser();

            if (empty($user)) {
                throw new IllegalStateException('Пользователь не задан. Войдите или зарегистрируйтесь.');
            }

            if ($user->hasItem($catalogItem)) {
                throw new \InvalidArgumentException("Материал &laquo;" . $catalogItem->title . "&raquo; уже оплачен и доступен для скачивания в <a href='" . URL::to('/user') . "'>личном кабинете.");
            }

            $order = new Order;

            $itemPrice = $this->orderItemPricePolicy->catalogItemPriceForUser($user, $catalogItem);

            $order->total = $itemPrice;
            $order->status = Order::STATUS_PENDING;
            $order->user_id = $user->id;
            $this->orderRepository->save($order);

            $orderItem = new OrderItem;
            $orderItem->price = $itemPrice;
            $orderItem->catalog_item_id = $catalogItemId;
            $order->items()->save($orderItem);

            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            throw $e;
        }


        if ($order->id) {
            return Redirect::to('/pay/' . $order->id);
        }

        App::abort(500, 'Could not create order');

    }

    public function completeOrder($orderId)
    {
        try {
            DB::beginTransaction();
            $order = $this->orderRepository->find($orderId);
            if (empty($order)) {
                throw new \InvalidArgumentException("Order #$orderId not found");
            }
            if ($order->status !== Order::STATUS_PENDING) {
                throw new \InvalidArgumentException("Order #$orderId already payed");
            }
            $order->status = Order::STATUS_COMPLETE;
            $this->orderRepository->save($order);
            DB::commit();
            Events::fire(new OrderCompleteEvent($orderId));
            return $order;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            throw $e;
        }
    }

    public function createOrderFromCart($userId)
    {
        try {
            DB::beginTransaction();
            $user = $this->userRepository->find($userId);
            $cart = $user->getOrCreateCart();
            if ($cart->items->isEmpty()) {
                throw new Exception("Невозможно создать заказ т.к. корзина пуста");
            }

            $total = 0;

            $order = new Order;
            $order->user_id = $userId;
            $order->status = Order::STATUS_PENDING;

            $orderItems = [];
            foreach ($cart->items as $item) {
                if ($user->hasItem($item->catalogItem)) continue;
                $priceForUser = $this->orderItemPricePolicy->catalogItemPriceForUser($user, $item->catalogItem);
                if ($priceForUser > 0) {
                    $orderItem = new OrderItem;
                    $orderItem->price = $priceForUser;
                    $orderItem->catalogItem()->associate($item->catalogItem);
                    $orderItems[] = $orderItem;
                    $total += $priceForUser;
                }
            }

            $order->total = $total;

            if (!$this->orderLimitPolicy->meetsLowerLimit($order)) {
                throw new Exception("Минимальная сумма заказа - " . $this->siteConfigProvider->getSiteConfig()->getMinOrderPrice() . " P.");
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
