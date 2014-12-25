<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 23.12.2014
 * Time: 20:31
 */

use Order\Order;

class PaymentsController extends BaseController
{

    public function pay()
    {

    }

    public function onpayApi()
    {
        $request = Request::instance();
        $content = $request->getContent();
        if (empty($content)) {
            return Response::json(array(
                'error' => 'Нет параметров'
            ), 400);
        }

        $json = json_decode($content, true);

        switch ($json['type']) {
            case 'check':

                $payFor = $json['pay_for'];
                $amount = $json['amount'];
                $currency = $json['way'];
                $mode = $json['mode'];
                $signature = $json['signature'];

                $order = Order::find($payFor);
                if (empty($order)
                    || ($order->total !== intval(100 * $amount))
                    || $currency != "RUR"
                    || $mode != "fix"
                    || $signature != md5("check;$payFor;$amount;$currency;$mode;" . Config::get('services.onpay.secret'))
                ) {
                    return Response::json(array(
                        "status" => false,
                        "pay_for" => $payFor,
                        "signature" => md5("check;false;$payFor;" . Config::get('services.onpay.secret'))
                    ), 400);
                }

                return Response::json(array(
                    "status" => true,
                    "pay_for" => $payFor,
                    "signature" => md5("check;true;$payFor;" . Config::get('services.onpay.secret'))
                ), 200);

                break;

            case 'pay':

                $payFor = $json['pay_for'];

                if (empty($json['order'])) {
                    return Response::json(array(
                        "code" => false
                    ), 400);
                }

                $amount = $json['order']['from_amount'];
                $currency = $json['order']['from_way'];
                $mode = $json['mode'];
                $signature = $json['signature'];

                $order = Order::find($payFor);
                if (empty($order)
                    || ($order->total !== intval(100 * $amount))
                    || $currency != "RUR"
                    || $signature != md5("check;$payFor;$amount;$currency;$mode;" . Config::get('services.onpay.secret'))
                ) {
                    return Response::json(array(
                        "code" => false,
                        "pay_for" => $payFor,
                        "signature" => md5("pay;false;$payFor;" . Config::get('services.onpay.secret'))
                    ), 400);
                }

                $orderService = App::make('OrderService');

                if ($orderService->payOrder($order->id)) {
                    return Response::json(array(
                        "code" => true,
                        "pay_for" => $payFor,
                        "signature" => md5("pay;true;$payFor;" . Config::get('services.onpay.secret'))
                    ), 200);
                } else {
                    return Response::json(array(
                        "code" => false,
                        "pay_for" => $payFor,
                        "signature" => md5("pay;false;$payFor;" . Config::get('services.onpay.secret'))
                    ), 500);
                }

                break;
        }

        return Response::json(array(
            "error"
        ), 404);


    }

}