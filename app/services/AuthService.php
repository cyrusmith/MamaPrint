<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 28.12.2014
 * Time: 0:09
 */
use \Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\App;
use \User\User;

class AuthService
{

    /**
     * Creates new guest User if it does not exists yet
     * @param $guestid
     * @return null|User
     */
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
            }
            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            $user = null;
        }

        return $user;

    }

    /**
     * Registers user replacing guest user
     * @throws Exception
     */
    public function registerUser(
        $name,
        $email,
        $password,
        $phone = null)
    {
        if (empty($name) || empty($email) || empty($password)) {
            throw new Exception("Illegal arguments of registerUser");
        }
        try {
            DB::beginTransaction();

            $existingUser = User::where('email', '=', $email)->first();

            if (!empty($existingUser)) {
                throw new Exception('Illegal state: user ' . $existingUser->name . ' already confirmed');
            }

            $user = App::make('UserService')->getUser();
            if (!$user) {
                $user = new User;
            } else if (!$user->isGuest()) {
                throw new Exception("Illegal state: current user is not guest.");
            }
            $user->guestid = null;
            $user->name = $name;
            $user->email = $email;
            $user->password = $password;
            $user->save();

            UserPending::where('email', '=', $user->email)->delete();

            $cart = $user->getOrCreateCart();

            $itemPricePolicy = App::make("OrderItemPricePolicy");
            foreach ($cart->items as $cartItem) {
                if ($itemPricePolicy->catalogItemPriceForUser($user, $cartItem->catalogItem) == 0) {
                    $cartItem->delete();
                }
            }

            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

    }

}