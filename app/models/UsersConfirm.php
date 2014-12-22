<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 22.12.2014
 * Time: 11:27
 */
class UsersConfirm extends Eloquent
{

    protected $table = 'users_confirm';

    public function user()
    {
        return $this->belongsTo('User');
    }

    public static function newInstanceFor($user)
    {
        $inst = new UsersConfirm;
        $inst->hash = str_random(40);
        $inst->user()->associate($user);
        return $inst;
    }

}