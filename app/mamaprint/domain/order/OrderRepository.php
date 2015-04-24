<?php

namespace mamaprint\domain\order;

use Order\Order;

class OrderRepository implements OrderRepositoryInterface
{

    public function find($id)
    {
        return Order::find($id);
    }

    public function save($entity)
    {
        // TODO: Implement save() method.
    }

    public function delete($entity)
    {
        // TODO: Implement delete() method.
    }
}