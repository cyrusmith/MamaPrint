<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 26.12.2014
 * Time: 17:11
 */
class Role extends Eloquent
{

    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';

    protected $table = 'roles';

    public function users()
    {
        return $this->belongsToMany('User');
    }

    public static function getByName($roleName)
    {
        return Role::where('name', '=', $roleName)->first();
    }

}