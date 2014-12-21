<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 21.12.2014
 * Time: 20:23
 */
class AuthController extends BaseController
{

    public function register()
    {

        Validator::extend('strongPassword', function ($attribute, $value, $parameters) {
            return mb_strlen($value) > 6 && preg_match('/[0-9]/', $value) && preg_match('/[a-z]/', $value) && preg_match('/[A-Z]/', $value);
        });

        $form = array(
            'password' => Input::get('password'),
            'password2' => Input::get('password2'),
            'email' => Input::get('email'),
            'name' => Input::get('name')
        );

        $validator = Validator::make(
            $form,
            array(
                'password' => array('required', 'strongPassword'),
                'password2' => array('required', 'same:password'),
                'name' => array('required'),
                'email' => array('email', 'required'),
            )
        );

        if ($validator->fails()) {
            return Redirect::to('register')->withErrors($validator)->with('form', $form);
        }

        DB::transaction(function () {

        });

    }

    public function login()
    {

        $email = Input::get('email');
        $password = Input::get('password');

        if (Auth::attempt(array('email' => $email, 'password' => $password))) {
            return Redirect::intended();
        } else {
            return View::make('user.profile', array('email' => $email));
        }

    }

}