<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 26.01.2015
 * Time: 11:25
 */

namespace Test;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use Order\Order;

class OrdersTestController extends \BaseController
{

    public function testDownloadLink()
    {
        $orderId = Input::get('order_id');
        try {
            $token = App::make('OrderService')->createDownloadLink($orderId);
            return Response::json([
                'token' => $token
            ], 200);
        } catch (\Exception $e) {
            return Response::json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function testCompleteOrder()
    {
        $orderId = Input::get('order_id');
        try {
            return Response::json([
                'order' => App::make('OrderService')->completeOrder($orderId, Input::get('user.email'))
            ], 200);
        } catch (\Exception $e) {
            return Response::json([
                'error' => $e->getMessage()
            ], 200);
        }
    }


}