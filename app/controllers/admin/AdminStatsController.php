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
use Illuminate\Support\Facades\Paginator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Order\Order;

class AdminStatsController extends AdminController
{

    public function getOrders()
    {
        $from = Input::get('from');
        $to = Input::get('to');
        $complete = Input::get('complete');

        $query = Order::orderBy('created_at', 'desc');
        if (!empty($from)) {
            $query->where('updated_at', '>=', $from);
        }
        if (!empty($from)) {
            $query->where('updated_at', '<=', $to);
        }
        if ($complete) {
            $query->where('status', '=', Order::STATUS_COMPLETE);
        }

        return View::make('admin.stats.orders', [
            'orders' => $query->paginate(50),
            'from' => $from,
            'to' => $to,
            'complete' => $complete,
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

    public function getCatalogitems()
    {
        $page = intval(Input::get('page'));
        if ($page == 0) {
            $page = 1;
        }

        $perPage = 50;

        $total = count(DB::select('select count(catalog_items.id) as count from orders inner join order_items on orders.id=order_items.order_id inner join catalog_items on order_items.catalog_item_id=catalog_items.id where orders.status=? group by catalog_items.id', [Order::STATUS_COMPLETE]));

        $items = DB::select('select catalog_items.id, catalog_items.price, count(catalog_items.id) as count, sum(order_items.price) as sum, catalog_items.title, order_items.price, orders.updated_at from orders inner join order_items on orders.id=order_items.order_id inner join catalog_items on order_items.catalog_item_id=catalog_items.id where orders.status=? group by catalog_items.id order by sum desc limit ' . (($page - 1) * $perPage) . ', ' . $perPage, [Order::STATUS_COMPLETE]);

        $pagedItems = Paginator::make($items, $total, $perPage);

        return $this->makeView('admin.stats.catalogitems', [
            'items' => $pagedItems
        ]);

    }

}