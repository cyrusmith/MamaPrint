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

        return Response::view('payments.success');

    }

    public function onpayApi()
    {

        Log::debug("onpayApi:");
        Log::debug(Input::get());

        switch (Input::get('type')) {
            case 'check':

                $payFor = Input::get('pay_for');

                $amount = floatval(Input::get('amount'));
                $amount = intval($amount * 100) / 100.0;
                $amountStr = number_format($amount, ($amount == intval($amount)) ? 1 : 2, '.', '');

                $currency = Input::get('way');
                $mode = Input::get('mode');
                $signature = Input::get('signature');

                $order = Order::find($payFor);
                if (empty($order)
                    || ($order->total !== intval(100 * $amount))
                    || ($currency != "RUR" && $currency != "TST")
                    || $mode != "fix"
                    || $signature != sha1("check;$payFor;$amountStr;$currency;$mode;" . Config::get('services.onpay.secret'))
                ) {
                    return Response::json(array(
                        "status" => false,
                        "pay_for" => $payFor,
                        "signature" => sha1("check;false;$payFor;" . Config::get('services.onpay.secret'))
                    ), 400);
                }

                return Response::json(array(
                    "status" => true,
                    "pay_for" => $payFor,
                    "signature" => sha1("check;true;$payFor;" . Config::get('services.onpay.secret'))
                ), 200);

                break;

            case 'pay':

                $payFor = Input::get('pay_for');

                $amount = Input::get('balance.amount');
                $amount = intval($amount * 100) / 100.0;
                $amountStr = number_format($amount, ($amount == intval($amount)) ? 1 : 2, '.', '');

                $currency = Input::get('balance.way');
                $signature = Input::get('signature');

                $paymentAmount = Input::get('payment.amount');
                $paymentAmount = intval($paymentAmount * 100) / 100.0;
                $paymentAmountStr = number_format($paymentAmount, ($paymentAmount == intval($paymentAmount)) ? 1 : 2, '.', '');

                $paymentWay = Input::get('payment.way');
                $balanceWay = $currency;

                Log::debug($signature);
                Log::debug(sha1("pay;$payFor;$paymentAmountStr;$paymentWay;$amountStr;$balanceWay;" . Config::get('services.onpay.secret')));
                Log::debug("pay;$payFor;$paymentAmountStr;$paymentWay;$amountStr;$balanceWay;" . Config::get('services.onpay.secret'));

                $order = Order::find($payFor);
                if (empty($order)
                    || ($order->total !== intval(100 * $amount))
                    || ($currency != "RUR" && $currency != "TST")
                    || $signature != sha1("pay;$payFor;$paymentAmountStr;$paymentWay;$amountStr;$balanceWay;" . Config::get('services.onpay.secret'))
                ) {
                    return Response::json(array(
                        "code" => false,
                        "pay_for" => $payFor,
                        "signature" => sha1("pay;false;$payFor;" . Config::get('services.onpay.secret'))
                    ), 400);
                }

                $orderService = App::make('OrderService');

                try {
                    Log::debug("About to payOrder");
                    $orderService->payOrder($order->id);
                    return Response::json(array(
                        "code" => true,
                        "pay_for" => $payFor,
                        "signature" => sha1("pay;true;$payFor;" . Config::get('services.onpay.secret'))
                    ), 200);
                } catch (Exception $e) {
                    Log::error('Fail to pa order: ' . $e->getMessage());
                    return Response::json(array(
                        "code" => false,
                        "pay_for" => $payFor,
                        "signature" => sha1("pay;false;$payFor;" . Config::get('services.onpay.secret'))
                    ), 500);

                }

                break;
        }

        return Response::json(array(
            "error"
        ), 404);


    }

}