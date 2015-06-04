<?php

use Cart\CartItem;
use \User\User;

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
        $pendingUser = UserPending::where('hash', '=', $hash)->first();
        if (empty($pendingUser)) {
            return Response::view('auth.confirm', array(
                'error' => 'Устаревший запрос'
            ), 400);
        }

        try {
            App::make("AuthService")->registerUser(
                $pendingUser->name,
                $pendingUser->email,
                $pendingUser->password);
            return Redirect::to('/login')->with('success', Lang::get('messages.thankyou_registration'));
        } catch (Exception $e) {
            return Response::view('auth.confirm', array(
                'error' => $e->getMessage()
            ), 400);
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

        list($address, $domain) = explode('@', $form["email"]);
        $emailLink = null;

        $emailsMap = [
            'mail.ru' => 'https://e.mail.ru/messages/inbox/',
            'bk.ru' => 'https://e.mail.ru/messages/inbox/',
            'inbox.ru' => 'https://e.mail.ru/messages/inbox/',
            'list.ru' => 'https://e.mail.ru/messages/inbox/',
            'gmail.com' => 'http://mail.google.com',
            'yandex.ru' => 'https://mail.yandex.ru',
            'rambler.ru' => 'https://mail.rambler.ru/',
            'lenta.ru' => 'https://mail.rambler.ru/',
            'autorambler.ru' => 'https://mail.rambler.ru/',
            'myrambler.ru' => 'https://mail.rambler.ru/',
            'ro.ru' => 'https://mail.rambler.ru/',
            'r0.ru' => 'https://mail.rambler.ru/',
        ];

        if (array_key_exists($domain, $emailsMap)) {
            $emailLink = $emailsMap[$domain];
        }

        return Redirect::to('/register/regcomplete')->with('emailLink', $emailLink);

    }

    public function login()
    {
        $email = Input::get('email');
        $password = Input::get('password');

        if (Auth::attempt(array('email' => $email, 'password' => $password))) {
            if (Auth::user()->hasRole(Role::getByName(Role::ROLE_ADMIN))) {
                return Redirect::intended('/admin/catalog');
            } else {
                return Redirect::intended('/');
            }

        } else {
            return Redirect::to('/login')->with('data', array(
                'email' => $email
            ))->with('error', 'Неправильные емейл или пароль');
        }

    }

    public function logout()
    {
        Auth::logout();
        return Redirect::to('/');
    }

}