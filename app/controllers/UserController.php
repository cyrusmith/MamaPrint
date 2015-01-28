<?php

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
        $name = Input::get('name');
        if (!empty($name)) {
            $user = Auth::user();
            $user->name = $name;
            $user->save();
        }
        return $this->withSuccessMessage(\Illuminate\Support\Facades\Redirect::to('/user/settings'), 'Имя успешно сохранено');
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
            if(!$isNewPassEquals) {
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