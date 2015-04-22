<?php

use Order\Order;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class OnpayService
{

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

        $order = Order::find($payFor);

        return (!empty($order)
            && ($order->total === intval(100 * $amount))
            && ($currency == "RUR" || $currency == "TST")
            && $mode == "fix"
            && $signature == sha1("check;$payFor;$amountStr;$currency;$mode;" . Config::get('services.onpay.secret')));

    }

    public function validatePayRequest(
        $payFor,
        $balanceAmount,
        $currency,
        $paymentAmount,
        $paymentWay,
        $signature
    ) {
        $balanceAmountStr = $this->amountStr($balanceAmount);
        $paymentAmountStr = $this->amountStr($paymentAmount);
        $balanceWay = $currency;

        Log::debug("pay;$payFor;$paymentAmountStr;$paymentWay;$balanceAmountStr;$balanceWay;" . Config::get('services.onpay.secret'));
        $checkSignature = sha1("pay;$payFor;$paymentAmountStr;$paymentWay;$balanceAmountStr;$balanceWay;" . Config::get('services.onpay.secret'));
        if (($currency != "RUR" && $currency != "TST")
            || $signature != $checkSignature
        ) {
            Log::debug("Payment verification fail");
            Log::debug("currency=" . $currency);
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