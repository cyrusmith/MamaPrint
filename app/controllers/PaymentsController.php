<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 23.12.2014
 * Time: 20:31
 */

use Order\Order;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

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

        $onpayService = App::make('OnpayService');

        switch (Input::get('type')) {
            case 'check':

                $payFor = Input::get('pay_for');
                $amount = Input::get('amount');
                $currency = Input::get('way');
                $mode = Input::get('mode');
                $signature = Input::get('signature');

                if ($onpayService->validateCheckRequest($payFor,
                    $amount,
                    $currency,
                    $mode,
                    $signature)
                ) {
                    return Response::json(array(
                        "status" => true,
                        "pay_for" => $payFor,
                        "signature" => sha1("check;true;$payFor;" . Config::get('services.onpay.secret'))
                    ), 200);

                } else {
                    Log::error("Check validation failed");
                    return Response::json(array(
                        "status" => false,
                        "pay_for" => $payFor,
                        "signature" => sha1("check;false;$payFor;" . Config::get('services.onpay.secret'))
                    ), 400);

                }

                break;

            case 'pay':

                $payFor = Input::get('pay_for');
                $balanceAmount = Input::get('balance.amount');
                $currency = Input::get('balance.way');
                $paymentAmount = Input::get('payment.amount');
                $paymentWay = Input::get('payment.way');
                $signature = Input::get('signature');

                if (!$onpayService->validatePayRequest($payFor,
                    $balanceAmount,
                    $currency,
                    $paymentAmount,
                    $paymentWay,
                    $signature)
                ) {
                    Log::error("Pay validation failed");
                    return Response::json(array(
                        "code" => false,
                        "pay_for" => $payFor,
                        "signature" => sha1("pay;false;$payFor;" . Config::get('services.onpay.secret'))
                    ), 400);
                }


                try {
                    App::make('OrderService')->completeOrder($payFor, Input::get('user.email'));
                    return Response::json(array(
                        "status" => true,
                        "pay_for" => $payFor,
                        "signature" => sha1("pay;true;$payFor;" . Config::get('services.onpay.secret'))
                    ), 200);
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
        Log::error("Unknown onpay method");
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