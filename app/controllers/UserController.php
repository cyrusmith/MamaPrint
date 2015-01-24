<?php

use Order\Order;

class UserController extends BaseController
{

    public function downloads()
    {
        $orders = Order::where('user_id', '=', Auth::user()->id)->where('status', '=', Order::STATUS_COMPLETE)->get();
        return View::make('user.downloads', array('orders' => $orders));
    }

}