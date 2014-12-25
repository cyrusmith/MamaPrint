<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 25.12.2014
 * Time: 19:11
 */
namespace Account;

class OperationRefill extends Operation
{
    public function changeAccountSum(Account $account)
    {
        $account->plus($this->amount);
    }

}