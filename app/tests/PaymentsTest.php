<?php

use Catalog\CatalogItem;
use Order\Order;

class PaymentsTest extends TestCase
{

    private $user;
    private $account;

    public function setUp()
    {
        parent::setUp();

        $item = new CatalogItem;
        $item->title = 'Item1';
        $item->price = 10099;
        $item->save();

        $item = new CatalogItem;
        $item->title = 'Item2';
        $item->price = 10000;
        $item->save();

        $this->user = new User;

        $this->user->name = "John";
        $this->user->email = "john@mail.ru";
        $this->user->password = "123";
        $this->user->save();

        $this->account = new \Account\Account();
        $this->account->balance = 12000;
        $this->account->currency = "RUR";
        $this->account->user()->associate($this->user);
        $this->account->save();

        $this->be($this->user);

    }

    public function testOnpayEmptyRequest()
    {
        $crawler = $this->client->request('POST', '/api/v1/payments/onpay');

        $this->assertTrue($this->client->getResponse()->isClientError());
    }

    public function testOnpayCheckInvalidOrderId()
    {
        $crawler = $this->client->request('POST', '/buyitem/1');
        $amount = 100.99;
        $crawler = $this->client->request('POST', '/api/v1/payments/onpay', array(
            "type" => "check",
            "pay_for" => "2",
            "amount" => $amount,
            "way" => "RUR",
            "mode" => "fix",
            "signature" => sha1("check;1;$amount;RUR;fix;" . Config::get('services.onpay.secret'))
        )/*, array(), array(), json_encode(array(
            "type" => "check",
            "pay_for" => "2",
            "amount" => $amount,
            "way" => "RUR",
            "mode" => "fix",
            "signature" => sha1("check;1;$amount;RUR;fix;" . Config::get('services.onpay.secret'))
        ))*/);

        $this->assertTrue($this->client->getResponse()->isClientError(), "Request should fail");
    }

    public function testOnpayCheckInvalidOrderSum()
    {
        $crawler = $this->client->request('POST', '/buyitem/1');

        $amount = 200.99;
        $crawler = $this->client->request('POST', '/api/v1/payments/onpay', array(
            "type" => "check",
            "pay_for" => "1",
            "amount" => $amount,
            "way" => "RUR",
            "mode" => "fix",
            "signature" => sha1("check;1;$amount;RUR;fix;" . Config::get('services.onpay.secret'))
        ));

        $this->assertTrue($this->client->getResponse()->isClientError(), "Request should fail");
    }

    public function testOnpayCheckInvalidCurrency()
    {
        $crawler = $this->client->request('POST', '/buyitem/1');

        $amount = 100.99;
        $crawler = $this->client->request('POST', '/api/v1/payments/onpay', array(
            "type" => "check",
            "pay_for" => "1",
            "amount" => $amount,
            "way" => "USD",
            "mode" => "fix",
            "signature" => sha1("check;1;$amount;USD;fix;" . Config::get('services.onpay.secret'))
        ));

        $this->assertTrue($this->client->getResponse()->isClientError(), "Request should fail");
    }

    public function testOnpayCheckInvalidMode()
    {
        $crawler = $this->client->request('POST', '/buyitem/1');

        $amount = 100.99;
        $crawler = $this->client->request('POST', '/api/v1/payments/onpay', array(
            "type" => "check",
            "pay_for" => "1",
            "amount" => $amount,
            "way" => "RUR",
            "mode" => "free",
            "signature" => sha1("check;1;$amount;RUR;free;" . Config::get('services.onpay.secret'))
        ));

        $this->assertTrue($this->client->getResponse()->isClientError(), "Request should fail");
    }

    public function testOnpayCheckInvalidSignature()
    {
        $crawler = $this->client->request('POST', '/buyitem/1');

        $amount = 100.99;
        $crawler = $this->client->request('POST', '/api/v1/payments/onpay', array(
            "type" => "check",
            "pay_for" => "1",
            "amount" => $amount,
            "way" => "RUR",
            "mode" => "fix",
            "signature" => sha1("spoil;check;1;$amount;RUR;fix;" . Config::get('services.onpay.secret'))
        ));

        $this->assertTrue($this->client->getResponse()->isClientError(), "Request should fail");
    }

    public function testOnpayCheckValid()
    {

        $crawler = $this->client->request('POST', '/buyitem/1');

        $amount = 100.99;
        $crawler = $this->client->request('POST', '/api/v1/payments/onpay', array(
            "type" => "check",
            "pay_for" => "1",
            "amount" => $amount,
            "way" => "RUR",
            "mode" => "fix",
            "signature" => sha1("check;1;$amount;RUR;fix;" . Config::get('services.onpay.secret'))
        ));

        $this->assertTrue($this->client->getResponse()->isOk(), "Response is not successful");
        $json = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($json["status"], true, "Status not true");
        $this->assertEquals($json["pay_for"], "1", "Payfor not valid");
        $this->assertEquals($json["signature"], sha1("check;true;1;" . Config::get('services.onpay.secret')), "signature not valid");

    }

    public function testOnpayCheckValidIntegerAmount()
    {

        $crawler = $this->client->request('POST', '/buyitem/2');

        $amount = 100.0;
        $crawler = $this->client->request('POST', '/api/v1/payments/onpay', array(
            "type" => "check",
            "pay_for" => "1",
            "amount" => $amount,
            "way" => "RUR",
            "mode" => "fix",
            "signature" => sha1("check;1;100.0;RUR;fix;" . Config::get('services.onpay.secret'))
        ));

        $this->assertTrue($this->client->getResponse()->isOk(), "Response is not successful");
        $json = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($json["status"], true, "Status not true");
        $this->assertEquals($json["pay_for"], "1", "Payfor not valid");
        $this->assertEquals($json["signature"], sha1("check;true;1;" . Config::get('services.onpay.secret')), "signature not valid");

    }

    private function getPayJson($payFor, $amount, $balanceWay = "RUR")
    {

        $amount = intval($amount * 100) / 100.0;
        $amount = number_format($amount, ($amount == intval($amount)) ? 1 : 2, '.', '');

        return [
            "type" => "pay",
            "signature" => sha1("pay;$payFor;$amount;RUR;$amount;RUR;" . Config::get('services.onpay.secret')),
            "pay_for" => $payFor,
            "way" => $balanceWay,
            "user" => [
                "email" => "mail@mail.ru",
                "phone" => "9631478946",
                "note" => ""
            ],
            "payment" => [
                "id" => 7121064,
                "date_time" => "2013-12-05T12:07:09+04:00",
                "amount" => $amount,
                "way" => $balanceWay,
                "rate" => 1.0,
                "release_at" => null
            ],
            "balance" => [
                "amount" => $amount,
                "way" => $balanceWay
            ],
            "order" => [
                "from_amount" => $amount,
                "from_way" => $balanceWay,
                "to_amount" => $amount,
                "to_way" => $balanceWay
            ]
        ];
    }

    public function testOnpayPayValid()
    {

        $crawler = $this->client->request('POST', '/buyitem/1');

        $payFor = "1";
        $amount = 100.99;
        $crawler = $this->client->request('POST', '/api/v1/payments/onpay', $this->getPayJson($payFor, $amount));

        $this->assertTrue($this->client->getResponse()->isOk(), "Response is not successful");
        $json = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($json["code"], true, "Status not true");
        $this->assertEquals($json["pay_for"], $payFor, "Payfor not valid");
        $this->assertEquals($json["signature"], sha1("pay;true;$payFor;" . Config::get('services.onpay.secret')), "signature not valid");

        $order = Order::find($payFor);
        $this->assertTrue(!empty($order), "No order found");
        $this->assertEquals($order->status, Order::STATUS_COMPLETE);

    }

    public function testOnpayPayValidintegerAmount()
    {

        $crawler = $this->client->request('POST', '/buyitem/2');

        $payFor = "1";
        $amount = 100.0;
        $crawler = $this->client->request('POST', '/api/v1/payments/onpay', $this->getPayJson($payFor, $amount));

        $this->assertTrue($this->client->getResponse()->isOk(), "Response is not successful");
        $json = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($json["code"], true, "Status not true");
        $this->assertEquals($json["pay_for"], $payFor, "Payfor not valid");
        $this->assertEquals($json["signature"], sha1("pay;true;$payFor;" . Config::get('services.onpay.secret')), "signature not valid");

        $order = Order::find($payFor);
        $this->assertTrue(!empty($order), "No order found");
        $this->assertEquals($order->status, Order::STATUS_COMPLETE);

    }

    public function testOnpayPayInvalidOrder()
    {
        $crawler = $this->client->request('POST', '/buyitem/1');

        $payFor = "2";
        $amount = 100.99;
        $crawler = $this->client->request('POST', '/api/v1/payments/onpay', $this->getPayJson($payFor, $amount));

        $this->assertTrue($this->client->getResponse()->isClientError(), "Response should fail");
        $json = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($json["code"], false, "Status not true");
        $this->assertEquals($json["pay_for"], $payFor, "Payfor not valid");
        $this->assertEquals($json["signature"], sha1("pay;false;$payFor;" . Config::get('services.onpay.secret')), "signature not valid");

    }

    public function testOnpayPayInvalidAmount()
    {
        $crawler = $this->client->request('POST', '/buyitem/1');

        $payFor = "1";
        $amount = 130.99;
        $crawler = $this->client->request('POST', '/api/v1/payments/onpay', $this->getPayJson($payFor, $amount));

        $this->assertTrue($this->client->getResponse()->isClientError(), "Response should fail");
        $json = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($json["code"], false, "Status not true");
        $this->assertEquals($json["pay_for"], $payFor, "Payfor not valid");
        $this->assertEquals($json["signature"], sha1("pay;false;$payFor;" . Config::get('services.onpay.secret')), "signature not valid");

    }

    public function testOnpayPayInvalidBalanceWay()
    {
        $crawler = $this->client->request('POST', '/buyitem/1');

        $payFor = "1";
        $amount = 100.99;
        $crawler = $this->client->request('POST', '/api/v1/payments/onpay', $this->getPayJson($payFor, $amount, "USD"));

        $this->assertTrue($this->client->getResponse()->isClientError(), "Response should fail");
        $json = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($json["code"], false, "Status not true");
        $this->assertEquals($json["pay_for"], $payFor, "Payfor not valid");
        $this->assertEquals($json["signature"], sha1("pay;false;$payFor;" . Config::get('services.onpay.secret')), "signature not valid");

    }

}
