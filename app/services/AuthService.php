<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 28.12.2014
 * Time: 0:09
 */
class AuthService
{

    public function registerGuest($guestid)
    {

        $needToRegister = false;
        if (empty($guestid)) {
            $guestid = str_random(40);
            $needToRegister = true;
        } else {
            $user = User::where('guestid', '=', $guestid)->first();
            $needToRegister = empty($user);
        }

        if ($needToRegister) {
            DB::transaction(function () use ($guestid) {
                $user = new User;
                $user->email = $guestid;
                $user->name = $guestid;
                $user->password = $guestid;
                $user->guestid = $guestid;
                $user->save();

                $account = new \Account\Account();
                $account->balance = 0;
                $account->currency = "RUR";
                $user->accounts()->save($account);
                $user->save();
            });

        }

        return $guestid;

    }

}