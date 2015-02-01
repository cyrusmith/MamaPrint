<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 25.12.2014
 * Time: 12:14
 */
class OrderService
{

    public function payOrder($orderId)
    {
        DB::beginTransaction();
        try {

            $order = \Order\Order::find($orderId);

            if (empty($order)) {
                throw new InvalidArgumentException("Order #$orderId not found");
            }

            if ($order->status !== \Order\Order::STATUS_PENDING) {
                throw new InvalidArgumentException("Order #$orderId already payed");
            }

            $sum = intval($order->total);

            $user = User::find($order->user->id);
            $account = $user->accounts()->first();

            $purchase = new \Account\OperationPurchase();
            $purchase->amount = $sum;
            $account->addOperation($purchase);
            $order->status = \Order\Order::STATUS_COMPLETE;

            $account->save();
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

            $user->catalogItems()->saveMany($catalogItems);

            DB::commit();

        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            throw $e;
        }
    }

    public function createDownloadLink($orderId)
    {

        if (empty($orderId)) {
            throw new InvalidArgumentException("Cannot create download link. Order id is empty.");
        }

        try {
            DB::beginTransaction();
            $order = \Order\Order::find($orderId);
            if (empty($order) || \Order\Order::STATUS_COMPLETE != $order->status) {
                throw new Exception("Нельзя создать временную ссылку т.к. заказ пока не оплачен или не существует");
            }
            $link = new DownloadLink();
            $link->order()->associate($order);
            $link->token = str_random(40);
            $link->save();

            DB::commit();
            return $link->token;
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

    }

    public function createOrderArchive($orderId)
    {
        $order = \Order\Order::find($orderId);

        if (empty($order) || \Order\Order::STATUS_COMPLETE != $order->status) {
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