<?php

use Order\Order;

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
        $name = \Illuminate\Support\Facades\Input::get('name');
        if (!empty($name)) {
            $user = Auth::user();
            $user->name = $name;
            $user->save();
        }
        return $this->withSuccessMessage(\Illuminate\Support\Facades\Redirect::to('/user/settings'), 'Имя успешно сохранено');
    }

    public function savePassword()
    {
        return \Illuminate\Support\Facades\Redirect::to('/user/settings');
    }

}