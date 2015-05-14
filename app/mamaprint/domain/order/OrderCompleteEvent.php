<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 14.05.2015
 * Time: 19:39
 */

namespace mamaprint\domain\order;


use mamaprint\infrastructure\events\AppEvent;

class OrderCompleteEvent implements AppEvent
{

    private $orderId;

    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    function getKey()
    {
        return "domainevent.ordercomplete";
    }
}