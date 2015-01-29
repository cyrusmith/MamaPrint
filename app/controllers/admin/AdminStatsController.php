<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 29.01.2015
 * Time: 11:18
 */

namespace Admin;

use Illuminate\Support\Facades\View;
use Order\Order;

class AdminStatsController extends AdminController
{

    public function getOrders()
    {
        $orders = Order::paginate(50);
        return View::make('admin.stats.orders', [
            'orders' => $orders
        ]);
    }

}