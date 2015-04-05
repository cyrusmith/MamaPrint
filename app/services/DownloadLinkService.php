<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 05.04.2015
 * Time: 17:10
 */
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class DownloadLinkService
{

    public function createAndSendLink($orderId, $email)
    {

        if (empty($orderId)) {
            throw new InvalidArgumentException("Cannot create download link. Order id is empty.");
        }

        try {
            DB::beginTransaction();
            $order = Order::find($orderId);
            if (empty($order) || Order::STATUS_COMPLETE != $order->status) {
                throw new Exception("Нельзя создать временную ссылку т.к. заказ пока не оплачен или не существует");
            }
            $link = new DownloadLink();
            $link->order()->associate($order);
            $link->token = str_random(40);
            $link->save();
            $user = $order->user;
            $todata = [
                'email' => $email,
                'name' => $user->isGuest ? '' : $user->name
            ];
            Mail::send('emails.payments.order', array(
                'orderId' => $orderId,
                'token' => $link->token
            ), function ($message) use ($todata) {
                Log::debug($todata);
                $message->from('noreply@' . $_SERVER['HTTP_HOST'])->to($todata['email'], empty($todata['name']) ? "Клиент mama-print" : $todata['name'])->subject('Покупка на сайте mama-print.ru');
            });

            DB::commit();
            return $link->token;
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

    }

}