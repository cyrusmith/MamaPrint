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

    public function pay($orderId)
    {

        $order = Order::find($orderId);

        if (empty($order)) {
            App::abort(404);
        }

        $user = App::make('UsersService')->getUser();

        if ($order->user->id !== $user->id) {
            App::abort(404);
        }

        return Response::view('payments.pay', [
            'order' => $order
        ]);

    }

    public function paymentSuccess($orderId)
    {

        $order = Order::find($orderId);

        if (empty($order)) {
            return Redirect::to('/');
        }

        $user = App::make('UsersService')->getUser();

        if ($order->user->id !== $user->id) {
            return Redirect::to('/');
        }

        if ($order->status === Order::STATUS_COMPLETE) {
            return Redirect::to('/download/' . $orderId);
        }

        return View::make('payments.success');

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

                if (empty($json['order']) || empty($json['payment']) || empty($json['balance'])) {
                    return Response::json(array(
                        "code" => false
                    ), 400);
                }

                $amount = $json['balance']['amount'];
                $currency = $json['balance']['way'];
                $signature = $json['signature'];

                $paymentAmount = $json['payment']['amount'];
                $paymentWay = $json['payment']['way'];
                $balanceAmount = $amount;
                $balanceWay = $currency;

                $order = Order::find($payFor);
                if (empty($order)
                    || ($order->total !== intval(100 * $amount))
                    || $currency != "RUR"
                    || $signature != md5("pay;$payFor;$paymentAmount;$paymentWay;$balanceAmount;$balanceWay;" . Config::get('services.onpay.secret'))
                ) {
                    return Response::json(array(
                        "code" => false,
                        "pay_for" => $payFor,
                        "signature" => md5("pay;false;$payFor;" . Config::get('services.onpay.secret'))
                    ), 400);
                }

                $orderService = App::make('OrderService');

                try {
                    $orderService->payOrder($order->id);
                    return Response::json(array(
                        "code" => true,
                        "pay_for" => $payFor,
                        "signature" => md5("pay;true;$payFor;" . Config::get('services.onpay.secret'))
                    ), 200);
                } catch (Exception $e) {
                    Log::error('Fail to pa order: ' . $e->getMessage());
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