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

            Log::debug($order);

            if (empty($order)) {
                throw new InvalidArgumentException("Order #$orderId not found");
            }

            $sum = intval($order->total);

            $user = User::find($order->user->id);
            $account = $user->accounts()->first();

            Log::debug($account);

            $purchase = new \Account\OperationPurchase();
            $purchase->amount = $sum;
            $account->addOperation($purchase);
            $order->status = \Order\Order::STATUS_COMPLETE;

            $account->save();
            $order->save();

            Log::debug("About to commit");

            DB::commit();

        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            throw $e;
        }
    }

}