<?php namespace Order;

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 23.12.2014
 * Time: 21:25
 */

use Eloquent;

class Order extends Eloquent
{

    /**
     * @deprecated
     */
    const STATUS_CART = 'cart';

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETE = 'complete';

    protected $table = 'orders';

    public function __construct($attributes = array())  {
        parent::__construct($attributes); // Eloquent
        $this->status = self::STATUS_PENDING;
    }

    public function items()
    {
        return $this->hasMany('Order\OrderItem');
    }

    public function getTotalAttribute($value)
    {
        return intval($value);
    }

    public function updateStatus($status)
    {
        if (!in_array($status, [self::STATUS_PENDING, self::STATUS_COMPLETE])) return;
        $this->status = $status;
    }

    public function isComplete()
    {
        return $this->status === self::STATUS_COMPLETE;
    }

}