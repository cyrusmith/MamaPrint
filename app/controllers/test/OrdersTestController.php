<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 26.01.2015
 * Time: 11:25
 */

namespace Test;

use Account\OperationRefill;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use Order\Order;

class OrdersTestController extends \BaseController
{

    public function testPayOrder()
    {
        $orderId = Input::get('order_id');
        try {

            $order = Order::find($orderId);

            $account = $order->user->accounts()->where('currency', '=', "RUR")->first();

            if (empty($account)) {
                throw new InvalidArgumentException('Could not find account with currency RUR');
            }

            $refill = new OperationRefill();
            $refill->amount = intval($order->total);

            $refill->gateway = 'onpay';
            $refill->gateway_operation_id = "12121212";
            $account->addOperation($refill);
            $account->save();

            App::make('OrderService')->payOrder($orderId);
        } catch (\Exception $e) {
            return Response::json([
                'message' => $e->getMessage()
            ], 500);

        }
        return Response::json([
        ], 200);

    }

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

}