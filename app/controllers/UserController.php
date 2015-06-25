<?php

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Order\Order;
use \Illuminate\Support\Facades\Input;
use \Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{

    public function downloads()
    {
        $orders = Order::where('user_id', '=', Auth::user()->id)->where('status', '=', Order::STATUS_COMPLETE)->get();
        return View::make('user.downloads', array('orders' => $orders));
    }

    public function viewCatalogItems()
    {
        $user = Auth::user();
        $items = $user->catalogItems;
        return View::make('user.catalogitems', [
            'items' => $items
        ]);
    }

    public function saveName()
    {
        $user = Auth::user();
        $name = Input::get('name');
        $email = Input::get('email');
        if (!empty($name)) {
            $user->name = $name;
            $user->save();
        } else {
            return $this->withSuccessMessage(Redirect::to('/user/settings'), 'Упс! Кажется, имя пусто. Мы оставили ваше имя прежним во избежание недоразумений.');
        }

        if (empty($user->email) && !empty($user->socialid)) {

            $validator = Validator::make(
                array('name' => $email),
                array('name' => 'required|email')
            );

            if (!$validator->fails()) {

                $existingUser = User::where('email', '=', $email)->first();
                if (!empty($existingUser)) {
                    return $this->withErrorMessage(Redirect::to('/user/settings'), 'Упс! Похоже, у вас уже есть подтвержденный аккаунт с email ' . $email . ". Попробуйте <a href='".URL::to("/logout")."'>выйти</a> и авторизоваться через email." );
                }

                Mail::send('emails.auth.emailconfirm', array(
                    'action' => URL::to("/user/emailconfirm") . "?hash=" . UserPending::createSocialConfirm($email, $user->socialid)
                ), function ($message) use ($email) {
                    $message->from('noreply@' . $_SERVER['HTTP_HOST'])->to($email, "Подтверждение email на " . $_SERVER['HTTP_HOST'])->subject('Подтверждение email на сайте mama-print.ru');
                });
                return $this->withSuccessMessage(Redirect::to('/user/settings'), 'Проверьте ваш email. Пройдите по ссылке в письме для подтверждения изменения email.');
            }

        }

        return $this->withSuccessMessage(Redirect::to('/user/settings'), 'Имя успешно сохранено');
    }

    public function savePassword()
    {
        $oldPassword = Input::get('oldpassword');
        $password = Input::get('newpassword');
        $password2 = Input::get('newpassword2');

        Validator::extend('validpass', function ($attribute, $value, $parameters) {
            return Hash::check($value, Auth::user()->password);
        });

        $validator = Validator::make(
            [
                'Старый пароль' => $oldPassword,
                'Новый пароль' => $password,
                'Повторить новый пароль' => $password2
            ],
            [
                'Старый пароль' => ['required', 'validpass'],
                'Новый пароль' => ['required'],
                'Повторить новый пароль' => ['required']
            ],
            [
                'validpass' => 'Неправильный старый пароль'
            ]
        );

        $isNewPassEquals = $password == $password2;

        if ($validator->fails() || !$isNewPassEquals) {
            $msgArr = [];
            $messages = $validator->messages();
            foreach ($messages->all() as $message) {
                $msgArr[] = $message;
            }
            if (!$isNewPassEquals) {
                $msgArr[] = 'Новый пароль и подтверждение не совпадают';
            }
            return $this->withErrorMessage(Redirect::to('/user/settings'), implode("<br>", $msgArr));
        }

        $user = Auth::user();
        $user->password = Hash::make($password);
        $user->save();

        return $this->withSuccessMessage(\Illuminate\Support\Facades\Redirect::to('/user/settings'), 'Пароль сохранен');
    }

}