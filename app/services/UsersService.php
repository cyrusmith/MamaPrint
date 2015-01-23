<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 24.12.2014
 * Time: 11:19
 */

use Account\Account;
use Account\OperationRefill;

class UsersService
{

    public function getUser()
    {
        if (Auth::check()) {
            return Auth::user();
        }
        $guestId = Cookie::get('guestid');
        if (!empty($guestId)) {
            return User::where('guestid', '=', $guestId)->first();
        } else {
            return null;
        }
    }

}