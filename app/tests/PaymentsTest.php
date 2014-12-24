<?php

use Catalog\CatalogItem;

class PaymentsTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();

        $item = new CatalogItem;
        $item->title = 'Item1';
        $item->price = 10099;
        $item->save();

        $user = new User;

        $user->name = "John";
        $user->email = "john@mail.ru";
        $user->password = "123";

        $user->save();

        $this->be($user);

    }

    /*public function testOnpayEmptyRequest()
    {
        $crawler = $this->client->request('GET', '/api/v1/payments/onpay');

        $this->assertTrue($this->client->getResponse()->isClientError());
    }

    public function testOnpayCheckInvalidOrderId() {
        $crawler = $this->client->request('POST', '/buyitem/1');

        $amount = 100.99;
        $crawler = $this->client->request('GET', '/api/v1/payments/onpay', array(), array(), array(), json_encode(array(
            "type" => "check",
            "pay_for" => "2",
            "amount" => $amount,
            "way" => "RUR",
            "mode" => "fix",
            "signature" => md5("check;1;$amount;RUR;fix;" . Config::get('services.onpay.secret'))
        )));

        $this->assertTrue($this->client->getResponse()->isClientError(), "Request should fail");
    }

    public function testOnpayCheckInvalidOrderSum() {
        $crawler = $this->client->request('POST', '/buyitem/1');

        $amount = 200.99;
        $crawler = $this->client->request('GET', '/api/v1/payments/onpay', array(), array(), array(), json_encode(array(
            "type" => "check",
            "pay_for" => "1",
            "amount" => $amount,
            "way" => "RUR",
            "mode" => "fix",
            "signature" => md5("check;1;$amount;RUR;fix;" . Config::get('services.onpay.secret'))
        )));

        $this->assertTrue($this->client->getResponse()->isClientError(), "Request should fail");
    }

    public function testOnpayCheckInvalidCurrency() {
        $crawler = $this->client->request('POST', '/buyitem/1');

        $amount = 100.99;
        $crawler = $this->client->request('GET', '/api/v1/payments/onpay', array(), array(), array(), json_encode(array(
            "type" => "check",
            "pay_for" => "1",
            "amount" => $amount,
            "way" => "USD",
            "mode" => "fix",
            "signature" => md5("check;1;$amount;USD;fix;" . Config::get('services.onpay.secret'))
        )));

        $this->assertTrue($this->client->getResponse()->isClientError(), "Request should fail");
    }

    public function testOnpayCheckInvalidMode() {
        $crawler = $this->client->request('POST', '/buyitem/1');

        $amount = 100.99;
        $crawler = $this->client->request('GET', '/api/v1/payments/onpay', array(), array(), array(), json_encode(array(
            "type" => "check",
            "pay_for" => "1",
            "amount" => $amount,
            "way" => "RUR",
            "mode" => "free",
            "signature" => md5("check;1;$amount;RUR;free;" . Config::get('services.onpay.secret'))
        )));

        $this->assertTrue($this->client->getResponse()->isClientError(), "Request should fail");
    }

    public function testOnpayCheckInvalidSignature() {
        $crawler = $this->client->request('POST', '/buyitem/1');

        $amount = 100.99;
        $crawler = $this->client->request('GET', '/api/v1/payments/onpay', array(), array(), array(), json_encode(array(
            "type" => "check",
            "pay_for" => "1",
            "amount" => $amount,
            "way" => "RUR",
            "mode" => "fix",
            "signature" => md5("spoil;check;1;$amount;RUR;fix;" . Config::get('services.onpay.secret'))
        )));

        $this->assertTrue($this->client->getResponse()->isClientError(), "Request should fail");
    }
    */

    public function testOnpayCheckValid()
    {

        $crawler = $this->client->request('POST', '/buyitem/1');

        $amount = 100.99;
        $crawler = $this->client->request('GET', '/api/v1/payments/onpay', array(), array(), array(), json_encode(array(
            "type" => "check",
            "pay_for" => "1",
            "amount" => $amount,
            "way" => "RUR",
            "mode" => "fix",
            "signature" => md5("check;1;$amount;RUR;fix;" . Config::get('services.onpay.secret'))
        )));

        $this->assertTrue($this->client->getResponse()->isOk(), "Response is not successful");
        $json = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($json["status"], true, "Status not true");
        $this->assertEquals($json["pay_for"], "1", "Payfor not valid");
        $this->assertEquals($json["signature"], md5("check;true;1;" . Config::get('services.onpay.secret')), "signature not valid");

    }

}
