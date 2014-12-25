<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 25.12.2014
 * Time: 19:07
 */

namespace Account;

use Eloquent;

class Account extends Eloquent
{

    protected $table = 'accounts';

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function operations()
    {
        return $this->hasMany('Account\Operation');
    }

    public function plus($amount)
    {
        $this->balance = intval($this->balance) + intval($amount);
    }

    public function minus($amount)
    {
        $newBalance = intval($this->balance) - intval($amount);
        if ($newBalance < 0) {
            throw new AccountException('Недостаточно средств');
        }
        $this->balance = $newBalance;
    }

    public function addOperation($operation)
    {
        if (empty($this->id)) {
            throw new Exception('Account not persisted yet');
        }
        if (!empty($operation->id)) {
            $existingOp = $this->operations()->find($operation->id);
            if (!empty($existingOp)) {
                throw new Exception('Account already has operation #' . $operation->id);
            }
        }
        $operation->changeAccountSum($this);
        $this->operations()->save($operation);
    }

}