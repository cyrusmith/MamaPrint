<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 29.01.2015
 * Time: 11:18
 */

namespace Admin;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Order\Order;

class AdminStatsController extends AdminController
{

    public function getOrders()
    {
        $orders = Order::orderBy('created_at', 'desc')->paginate(50);
        return View::make('admin.stats.orders', [
            'orders' => $orders
        ]);
    }

    public function postOrder($orderId)
    {
        DB::transaction(function () use ($orderId) {
            $order = Order::find($orderId);
            if (empty($order)) {
                App::abort(400, 'Заказ не найден');
            }
            if (Input::has('status')) {
                $status = Input::get('status');
                $order->updateStatus($status);
            }
            $order->save();
        });
        return $this->withSuccessMessage(Redirect::back(), 'Заказ обновлен');
    }

    public function getOrder($orderId)
    {
        $order = Order::find($orderId);
        if (empty($order)) {
            App::abort(404, 'Заказ не найден');
        }

        $this->setPageTitle('Заказ');

        return $this->makeView('admin.stats.order', [
            'order' => $order
        ]);
    }

}