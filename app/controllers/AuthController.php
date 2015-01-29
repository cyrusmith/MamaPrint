<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 21.12.2014
 * Time: 20:23
 */

use Cart\CartItem;

class AuthController extends BaseController
{

    public function confirm()
    {

        $hash = Input::get('hash');

        if (empty($hash)) {
            Response::view('auth.confirm', array(
                'error' => 'Неверный запрос'
            ), 400);
        }

        try {

            DB::beginTransaction();

            $pendingUser = UserPending::where('hash', '=', $hash)->first();
            if (empty($pendingUser)) {
                return Response::view('auth.confirm', array(
                    'error' => 'Устаревший запрос'
                ), 400);
            }

            $user = User::where('email', '=', $pendingUser->email)->first();

            if (!empty($user)) {
                return Response::view('auth.confirm', array(
                    'error' => 'Пользователь уже подтвержден'
                ), 400);
            }

            $user = new User;
            $user->email = $pendingUser->email;
            $user->name = $pendingUser->name;
            $user->password = $pendingUser->password;
            $user->save();

            $account = new \Account\Account();
            $account->balance = 0;
            $account->currency = "RUR";
            $user->accounts()->save($account);

            UserPending::where('email', '=', $user->email)->delete();

            DB::commit();

            return Redirect::to('/login')->with('message', Lang::get('messages.thankyou_registration'));

        } catch (Exception $e) {
            DB::rollback();
        }
    }

    public function register()
    {

        Validator::extend('strongPassword', function ($attribute, $value, $parameters) {
            return mb_strlen($value) >= 6 /*&& preg_match('/[0-9]/', $value) && preg_match('/[a-z]/', $value) && preg_match('/[A-Z]/', $value)*/
                ;
        });

        Validator::extend('checkUserWithEmail', function ($attribute, $value, $parameters) {
            $existingUser = null;
            try {
                DB::beginTransaction();
                $existingUser = User::where('email', '=', $value)->first();
                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
            }
            return empty($existingUser);
        });

        $form = array(
            'password' => Input::get('password'),
            'password2' => Input::get('password2'),
            'email' => mb_strtolower(Input::get('email')),
            'name' => Input::get('name')
        );

        $validator = Validator::make(
            $form,
            [
                'password' => array('required', 'strongPassword'),
                'password2' => array('required', 'same:password'),
                'name' => array('required'),
                'email' => array('email', 'required', 'checkUserWithEmail'),
            ],
            [
                'strong_password' => 'Пароль должен содержать не менее шести символов'
            ]
        );


        if ($validator->fails()) {
            return Redirect::to('register')->withErrors($validator)->with('form', $form);
        }

        $userPending = new UserPending;
        $userPending->email = $form["email"];
        $userPending->name = $form["name"];
        $userPending->password = Hash::make($form["password"]);
        $userPending->hash = str_random(40);
        $userPending->save();

        if (!$userPending->id) {
            App::abort(500);
        }

        Mail::send('emails.auth.register', array(
            'action' => URL::action("AuthController@confirm", array("hash" => $userPending->hash))
        ), function ($message) use ($userPending) {
            $message->from('noreply@' . $_SERVER['HTTP_HOST'])->to($userPending->email, $userPending->name)->subject('Регистрация на сайте mama-print.ru');
        });

        return Redirect::to('/register/regcomplete');

    }

    public function login()
    {
        $email = Input::get('email');
        $password = Input::get('password');

        if (Auth::attempt(array('email' => $email, 'password' => $password))) {

            $guestId = Session::get('guestid');
            if (empty($guestId)) {
                $guestId = Cookie::get('guestid');
            }
            if (!empty($guestId)) {

                $authUser = Auth::user();

                if (empty($authUser->cart) || $authUser->cart->items->isEmpty()) {

                    $tmpUser = User::where('guestid', '=', $guestId)->first();
                    if (!empty($tmpUser)) {
                        $tmpCart = $tmpUser->cart;
                        if (!empty($tmpCart) && !$tmpCart->items->isEmpty()) {
                            try {
                                DB::beginTransaction();
                                $authUserCart = $authUser->getOrCreateCart();
                                foreach ($tmpUser->cart->items as $tmpCartItem) {
                                    $cartItem = new CartItem;
                                    $cartItem->catalogItem()->associate($tmpCartItem->catalogItem);
                                    $authUserCart->items()->save($cartItem);
                                }
                                $tmpUser->cart->delete();
                                DB::commit();
                            } catch (Exception $e) {
                                \Illuminate\Support\Facades\Log::error($e);
                                DB::rollback();
                            }
                        }
                    }

                }

            }
            Session::set('guestid', null);
            Cookie::queue('guestid', null, 0);

            return Redirect::intended('/');
        } else {
            return Redirect::to('/login')->with('data', array(
                'error' => 'Неправильные емейл или пароль',
                'email' => $email
            ));
        }

    }

    public function logout()
    {
        Auth::logout();
        return Redirect::to('/');
    }

}