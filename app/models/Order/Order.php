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

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETE = 'complete';

    protected $table = 'orders';

    public function items()
    {
        return $this->hasMany('Order\OrderItem');
    }

    public function user()
    {
        return $this->belongsTo('\User\User');
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