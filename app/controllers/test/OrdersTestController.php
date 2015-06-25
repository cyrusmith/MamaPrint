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


class OrdersTestController extends \BaseController
{

    public function __construct(\OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function testPayOrder()
    {
        $orderId = Input::get('order_id');
        $paymentAmount = Input::get('amount');
        $paymentCurrency = Input::get('cur');
        $paymentTransactionId = Input::get('trans_id');
        $paymentEmail = Input::get('email');
        try {
            $this->orderService->payOrder($orderId, $paymentAmount, $paymentCurrency, $paymentTransactionId, $paymentEmail);
        } catch (\Exception $e) {
            return Response::json([
                'message' => $e->getMessage()
            ], 203);
        }
        return Response::json([
            $orderId,
            $paymentAmount,
            $paymentCurrency,
            $paymentTransactionId,
            $paymentEmail
        ], 200);

    }


}