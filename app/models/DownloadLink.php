<?php

class DownloadLink extends Eloquent
{

    protected $table = 'download_links';

    public function order()
    {
        return $this->hasOne('Order\Order');
    }

    public static function create($order)
    {

        if (empty($order) || \Order\Order::STATUS_COMPLETE != $order->status) {
            throw new Exception("Нельзя создать временную ссылку т.к. заказ пока не оплачен или не существует");
        }

        $link = new DownloadLink;
        $link->order()->associate($order);
        $link->token = str_random(40);
        $link->save();

        return $link;
    }

}