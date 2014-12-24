<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 24.12.2014
 * Time: 11:19
 */
class UsersService
{

    public function getUser()
    {
        if (Auth::check()) {
            return Auth::user();
        }
        $guestId = Session::get('guiestid');
        $user = null;
        if (!empty($guestId)) {
            $user = User::where('guestid', '=', $guestId)->first();
        } else {
            return null;
        }

        if (!empty($user)) {
            return $user;
        }

        $user = new User;
        $user->guestid = $guestId;
        $user->email = $guestId;
        $user->name = $guestId;
        $user->password = $guestId;
        $user->save();
        return $user;
    }

}