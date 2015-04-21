<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 23.12.2014
 * Time: 20:31
 */

use Order\Order;
use Account\OperationRefill;

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
            'order' => $order,
            'text' => Article::getArticleContent('paymentinstructions')
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

        return Response::view('payments.success', [
            'order' => $order
        ]);

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
                $amountStr = $this->amountStr($amount);

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
                $orderId = $payFor;

                $balanceAmount = Input::get('balance.amount');
                $balanceAmountStr = $this->amountStr($balanceAmount);

                $currency = Input::get('balance.way');
                $signature = Input::get('signature');

                $paymentAmount = Input::get('payment.amount');
                $paymentAmountStr = $this->amountStr($paymentAmount);

                $paymentWay = Input::get('payment.way');
                $balanceWay = $currency;

                Log::debug("pay;$payFor;$paymentAmountStr;$paymentWay;$balanceAmountStr;$balanceWay;" . Config::get('services.onpay.secret'));
                $checkSignature = sha1("pay;$payFor;$paymentAmountStr;$paymentWay;$balanceAmountStr;$balanceWay;" . Config::get('services.onpay.secret'));
                if (($currency != "RUR" && $currency != "TST")
                    || $signature != $checkSignature
                ) {
                    Log::debug("Payment verification fail");
                    Log::debug("currency=" . $currency);
                    Log::debug("signatures: " . $signature . " " . $checkSignature);
                    return Response::json(array(
                        "code" => false,
                        "pay_for" => $payFor,
                        "signature" => sha1("pay;false;$payFor;" . Config::get('services.onpay.secret'))
                    ), 400);
                }

                try {
                    App::make('OrderService')->completeOrder($orderId, Input::get('user.email'));
                } catch (Exception $e) {
                    Log::error('Fail to pay order: ' . $e->getMessage());
                    return Response::json(array(
                        "code" => false,
                        "pay_for" => $payFor,
                        "signature" => sha1("pay;false;$payFor;" . Config::get('services.onpay.secret'))
                    ), 500);
                }

                break;
        }

        return Response::json(array(
            "Error"
        ), 404);


    }

    private function amountStr($amount)
    {
        $str = strval($amount);
        if ($amount == intval($amount)) {
            $str = $str . ".0";
        }
        return $str;
    }

}