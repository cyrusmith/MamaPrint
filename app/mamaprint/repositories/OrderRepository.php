<?php

namespace mamaprint\repositories;

use Order\Order;

class OrderRepository implements OrderRepositoryInterface
{

    public function find($id)
    {
        return Order::find($id);
    }
}