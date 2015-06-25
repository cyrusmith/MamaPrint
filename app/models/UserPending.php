<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 22.12.2014
 * Time: 11:27
 */
class UserPending extends Eloquent
{

    protected $table = 'user_pending';

    public static function createSocialConfirm($email, $socialid) {
        $hash = str_random(40);
        $userPending = new UserPending();
        $userPending->email = $email;
        $userPending->name = $socialid;
        $userPending->hash = $hash;
        $userPending->password = Hash::make($hash);
        $userPending->save();
        return $hash;
    }

}