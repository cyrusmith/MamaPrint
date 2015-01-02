<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 27.12.2014
 * Time: 18:06
 */
class AuthTest extends TestCase
{

    public function testRegisterGuestHasCookieHasUser()
    {

        $guestid = "123";

        $user = new User;
        $user->guestid = $guestid;
        $user->email = $guestid;
        $user->name = $guestid;
        $user->password = $guestid;
        $user->save();

        App::make('AuthService')->registerGuest($guestid);

        $user2 = User::where('guestid', '=', $guestid);

        $this->assertEquals(1, $user2->count());

    }

    public function testRegisterGuestHasCookieNoUser()
    {

        $guestid = "123";

        App::make('AuthService')->registerGuest($guestid);

        $user2 = User::where('guestid', '=', $guestid);

        $this->assertEquals(1, $user2->count());
        $this->assertEquals(1, $user2->first()->accounts()->first()->id);

    }

    public function testRegisterGuestNoCookie()
    {
        $guestid = App::make('AuthService')->registerGuest(null);

        $user2 = User::all()->first();

        $this->assertEquals($guestid, $user2->guestid);
        $this->assertEquals(1, $user2->accounts()->first()->id);
    }

}