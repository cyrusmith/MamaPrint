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

            $sum = intval($order->total);
            $account = $order->user->account;

            $purchase = new \Account\OperationPurchase();
            $purchase->amount = $sum;
            $account->addOperation($purchase);
            $order->status = \Order\Order::STATUS_COMPLETE;

            $account->save();
            $order->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            throw $e;
        }
    }

}