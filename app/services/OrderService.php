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

            $catalogItems = [];
            foreach ($order->items as $item) {
                $catalogItems[] = $item->catalogItem;
            }

            $user->catalogItems()->saveMany($catalogItems);

            DB::commit();

        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            throw $e;
        }
    }

}