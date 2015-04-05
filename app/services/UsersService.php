<?php

use \User\User;

class UsersService
{

    public function getUser()
    {
        if (Auth::check()) {
            return Auth::user();
        }
        $guestId = Session::get('guestid');
        if (!empty($guestId)) {
            return User::where('guestid', '=', $guestId)->first();
        } else {
            return null;
        }
    }

}