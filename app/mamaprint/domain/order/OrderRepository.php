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
        if ($entity instanceof Order) {
            $entity->save();
            return $entity;
        }
        return null;
    }

    public function delete($entity)
    {
        if ($entity instanceof Order) {
            $entity->delete();
        }
    }
}