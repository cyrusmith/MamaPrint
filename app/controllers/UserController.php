<?php

use Order\Order;

class UserController extends BaseController
{

    public function downloads()
    {
        $orders = Order::where('user_id', '=', Auth::user()->id)->get();
        return View::make('user.downloads', array('orders' => $orders));
    }

}