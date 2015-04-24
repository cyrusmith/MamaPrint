<?php

use Order\Order;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use mamaprint\repositories\OrderRepositoryInterface;

class OnpayService
{

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function validateCheckRequest(
        $payFor,
        $amount,
        $currency,
        $mode,
        $signature)
    {

        $amount = floatval($amount);
        $amount = intval($amount * 100) / 100.0;
        $amountStr = $this->amountStr($amount);

        $order = $this->orderRepository->find($payFor);
        return (!empty($order)
            && ($order->total === intval(100 * $amount))
            && ($currency == "RUR" || $currency == "TST")
            && $mode == "fix"
            && $signature == sha1("check;$payFor;$amountStr;$currency;$mode;" . Config::get('services.onpay.secret')));

    }

    public function validatePayRequest(
        $payFor,
        $balanceAmount,
        $balanceWay,
        $paymentAmount,
        $paymentWay,
        $signature
    )
    {
        $balanceAmount = floatval($balanceAmount);
        $balanceAmount = intval($balanceAmount * 100) / 100.0;

        $paymentAmount = floatval($paymentAmount);
        $paymentAmount = intval($paymentAmount * 100) / 100.0;

        $balanceAmountStr = $this->amountStr($balanceAmount);
        $paymentAmountStr = $this->amountStr($paymentAmount);

        $order = $this->orderRepository->find($payFor);

        Log::debug("pay;$payFor;$paymentAmountStr;$paymentWay;$balanceAmountStr;$balanceWay;" . Config::get('services.onpay.secret'));
        $checkSignature = sha1("pay;$payFor;$paymentAmountStr;$paymentWay;$balanceAmountStr;$balanceWay;" . Config::get('services.onpay.secret'));
        if (empty($order) || ($order->status != Order::STATUS_PENDING) || ($balanceWay != "RUR" && $balanceWay != "TST")
            || $signature != $checkSignature
        ) {
            Log::debug("Payment verification fail");
            Log::debug("currency=" . $balanceWay);
            Log::debug("signatures: " . $signature . " " . $checkSignature);
            return false;
        }

        return true;
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