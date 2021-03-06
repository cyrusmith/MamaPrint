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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Paginator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Order\Order;

class AdminStatsController extends AdminController
{

    public function __construct(\OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function getOrders()
    {
        $from = Input::get('from');
        $to = Input::get('to');
        $complete = Input::get('complete');

        $query = Order::orderBy('created_at', 'desc');
        if (!empty($from)) {
            $query->where('created_at', '>=', $from);
        }
        if (!empty($from)) {
            $query->where('created_at', '<=', $to);
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
        $order = Order::find($orderId);
        if (empty($order)) {
            App::abort(400, 'Заказ не найден');
        }
        $status = Input::has('status');
        if($status == "complete") {
            try {
                $this->orderService->payOrder($orderId, $order->total / 100.0, "RUR", "-1");
                return $this->withSuccessMessage(Redirect::back(), 'Заказ завершен');
            }
            catch(\Exception $e) {
                Log::error($e);
                return $this->withErrorMessage(Redirect::back(), $e->getMessage());
            }
        }
        return $this->withSuccessMessage(Redirect::back(), 'Заказ не обновлен');
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

        $moscowTz = new \DateTimeZone('Europe/Moscow');
        $utcTz = new \DateTimeZone("UTC");

        $fromInput = Input::get('from');
        $toInput = Input::get('to');
        $searchtag = Input::get('searchtag');

        if (empty($fromInput)) {
            $from = new \DateTime('1970-01-01', $moscowTz);
        } else {
            $from = new \DateTime($fromInput, $moscowTz);
        }

        if (empty($toInput)) {
            $to = new \DateTime('NOW', $moscowTz);
        } else {
            $to = new \DateTime($toInput, $moscowTz);
        }

        $from->setTimezone($utcTz);
        $to->setTimezone($utcTz);

        $dateWhere = "orders.created_at BETWEEN STR_TO_DATE('" . $from->format('Y-m-d') . " 00:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('" . $to->format('Y-m-d') . " 23:59:59', '%Y-%m-%d %H:%i:%s')";

        $this->setPageTitle('Статистика по материалам');

        $page = intval(Input::get('page'));
        if ($page == 0) {
            $page = 1;
        }

        $perPage = 50;

        $total = count(DB::select("select
  catalog_items.id,
  (select group_concat(t0.tag separator ',') from taggables as tbl0 left join tags as t0 on t0.id=tbl0.tag_id where t0.type like 'tag' and tbl0.taggable_id=catalog_items.id and tbl0.taggable_type like 'Catalog\\\CatalogItem' ESCAPE '|' group by tbl0.taggable_id)
  as tags
from order_items
left join orders on orders.id=order_items.order_id
left join catalog_items on order_items.catalog_item_id=catalog_items.id
where
  orders.status=? and " . $dateWhere . "
group by catalog_items.id having tags LIKE '%" . $searchtag . "%'", [Order::STATUS_COMPLETE]));

        //$items = DB::select('select catalog_items.id, catalog_items.price, count(catalog_items.id) as count, sum(order_items.price) as sum, catalog_items.title, order_items.price, orders.updated_at from orders inner join order_items on orders.id=order_items.order_id inner join catalog_items on order_items.catalog_item_id=catalog_items.id where orders.status=? group by catalog_items.id order by sum desc limit ' . (($page - 1) * $perPage) . ', ' . $perPage, [Order::STATUS_COMPLETE]);
        $items = DB::select("select
  catalog_items.id,
  catalog_items.price,
  catalog_items.registered_price,
  catalog_items.old_price,
  catalog_items.title,
  catalog_items.slug,
  count(catalog_items.id) as number_bought,
  sum(order_items.price) as total,
  (select group_concat(t0.tag separator ',') from taggables as tbl0 left join tags as t0 on t0.id=tbl0.tag_id where t0.type like 'tag' and tbl0.taggable_id=catalog_items.id and tbl0.taggable_type like 'Catalog\\\CatalogItem' ESCAPE '|' group by tbl0.taggable_id)
  as tags
from order_items
left join orders on orders.id=order_items.order_id
left join catalog_items on order_items.catalog_item_id=catalog_items.id
where
  orders.status=? and " . $dateWhere . "
group by catalog_items.id having tags LIKE '%" . $searchtag . "%' order by number_bought desc limit " . (($page - 1) * $perPage) . ', ' . $perPage, [Order::STATUS_COMPLETE]);

        $pagedItems = Paginator::make($items, $total, $perPage);

        return $this->makeView('admin.stats.catalogitems', [
            'items' => $pagedItems->appends(Input::except('page')),
            'from' => Input::get('from'),
            'to' => Input::get('to'),
            'searchtag' => $searchtag
        ]);

    }

}