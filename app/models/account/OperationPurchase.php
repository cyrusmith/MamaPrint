<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 25.12.2014
 * Time: 19:11
 */
namespace Account;

class OperationPurchase extends Operation
{
    public function changeAccountSum(Account $account) {
        $account->minus($this->amount);
    }
}