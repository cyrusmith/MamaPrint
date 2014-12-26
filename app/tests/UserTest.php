<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 26.12.2014
 * Time: 17:29
 */
class UserTest extends TestCase
{

    public function testUserRoleDefault()
    {

        $user = new User;
        $user->email = 'aaa@mail.ru';
        $user->name = 'User1';
        $user->password = 'pass1';
        $user->save();

        $roles = $user->getRolesOrDefault();

        $this->assertEquals(1, $roles->count());
        $this->assertEquals(Role::ROLE_USER, $roles->first()->name);
    }

    public function testUserRoleAdmin()
    {

        $user = new User;
        $user->email = 'aaa@mail.ru';
        $user->name = 'User1';
        $user->password = 'pass1';
        $user->save();

        $user->roles()->save(Role::getByName(Role::ROLE_ADMIN));
        $user->save();

        $roles = $user->getRolesOrDefault();

        $this->assertEquals(1, $roles->count());
        $this->assertEquals(Role::ROLE_ADMIN, $roles->first()->name);
    }

}