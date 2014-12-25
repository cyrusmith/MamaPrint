<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 25.12.2014
 * Time: 19:11
 */
namespace Account;

use BaseModel;

class Operation extends BaseModel
{

    protected $table = 'operations';

    protected $stiClassField = 'type';
    protected $stiBaseClass = 'Account\Operation';

    public function account()
    {
        return $this->belongsTo('Account');
    }

    public function changeAccountSum(Account $account)
    {
    }

}