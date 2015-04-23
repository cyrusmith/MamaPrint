<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Order\Order;


class PaymentsTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->orderMock = Mockery::mock('mamaprint\repositories\OrderRepositoryInterface');
        $this->app->instance('mamaprint\repositories\OrderRepositoryInterface', $this->orderMock);
    }

    public function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testCheckRequest()
    {

        $dummySecret = "secret";

        $dummyOrder = new Order();
        $dummyOrder->total = 1205;
        $this->orderMock->shouldReceive('find')->once()->andReturn($dummyOrder);

        Config::shouldReceive('get')->with('services.onpay.secret')->andReturn($dummySecret);

        $service = App::make('OnpayService');

        $payFor = "123";
        $amount = "12.05";
        $currency = "RUR";
        $mode = "fix";

        $signature = sha1("check;$payFor;12.05;$currency;$mode;" . $dummySecret);

        $result = $service->validateCheckRequest(
            $payFor,
            $amount,
            $currency,
            $mode,
            $signature);

        $this->assertTrue($result);

    }

    public function testPayRequest()
    {

        //TODO test that order is not payed

        $dummySecret = "secret";

        $dummyOrder = new Order();
        $dummyOrder->total = 1205;
        $this->orderMock->shouldReceive('find')->once()->andReturn($dummyOrder);

        Config::shouldReceive('get')->with('services.onpay.secret')->andReturn($dummySecret);

        $service = App::make('OnpayService');

        $payFor = "123";
        $balanceAmount = "12.05";
        $paymentAmount = "13.0";
        $balanceWay = "RUR";
        $paymentWay = "RUR";

        $signature = sha1("pay;$payFor;13.0;$paymentWay;12.05;$balanceWay;" . $dummySecret);;

        $result = $service->validatePayRequest(
            $payFor,
            $balanceAmount,
            $balanceWay,
            $paymentAmount,
            $paymentWay,
            $signature
        );

        $this->assertTrue($result);

    }

}
