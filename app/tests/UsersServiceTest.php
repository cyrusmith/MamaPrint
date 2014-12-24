<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 24.12.2014
 * Time: 11:38
 */
class UsersServiceTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->userService = App::make('UsersService');
    }

    public function testGetUserAuth()
    {
        $user = User::create(array(
            'name' => 'john',
            'email' => 'john@mail.ru',
            'password' => '123'
        ));
        $this->be($user);
        $user2 = $this->userService->getUser();
        $this->assertTrue($user->email === $user2->email);
    }

    public function testGetUserGuestNotExistsNoGuestId()
    {
        $user = $this->userService->getUser();
        $this->assertNull($user);
    }

    public function testGetUserGuestNotExistsWithGuestId()
    {
        $questId = "23498web23489wefib234";
        Session::shouldReceive('get')->once()->with('guiestid')->andReturn($questId);;
        $user = $this->userService->getUser();
        $this->assertTrue($user->guestid === $questId);
    }

    public function testGetUserGuestExists()
    {
        $questId = "2346535234234";
        $user = User::create(array(
            'guestid' => $questId,
            'name' => $questId,
            'email' => $questId,
            'password' => $questId
        ));
        Session::shouldReceive('get')->once()->with('guiestid')->andReturn($questId);;

        $user = $this->userService->getUser();

        $this->assertTrue($user->guestid === $questId);
    }

}