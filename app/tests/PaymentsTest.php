<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Order\Order;

class PaymentsTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->mock  = Mockery::mock('Order');
        $this->app->instance('Order', $this->mock);
    }

    public function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testSomethingIsTrue()
    {

        $dummySecret = "secret";

        Config::shouldReceive('get')->with('services.onpay.secret')->andReturn($dummySecret);
        Config::shouldReceive('offsetGet')->andReturn(true);

        $this->mock->shouldReceive('find')->with("123")->once()->andReturn(123);

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

}
