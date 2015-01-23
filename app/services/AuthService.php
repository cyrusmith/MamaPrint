<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 28.12.2014
 * Time: 0:09
 */
use \Illuminate\Support\Facades\DB;

class AuthService
{

    public function registerGuest($guestid)
    {

        try {

            DB::beginTransaction();

            $user = null;

            $needToRegister = false;
            if (empty($guestid)) {
                $needToRegister = true;
                $guestid = str_random(40);
            } else {
                $user = User::where('guestid', '=', $guestid)->first();
                if (empty($user)) {
                    $guestid = str_random(40);
                    $needToRegister = true;
                }
            }

            if ($needToRegister) {
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

            }
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            $user = null;
        }

        return $user;

    }

}