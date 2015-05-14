<?php

use Order\Order;

use Illuminate\Support\Facades\DB;


class OrderTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->orderRepoMock = Mockery::mock('mamaprint\domain\order\OrderRepositoryInterface');
    }

    public function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testCompleteOrder()
    {

        $dummyOrder = new Order();
        $dummyOrder->id = 1;
        $dummyOrder->total = 1205;
        $dummyOrder->user_id = 2;

        DB::shouldReceive('beginTransaction')->andReturn(null);
        DB::shouldReceive('commit')->andReturn(null);
        DB::shouldReceive('rollback')->andReturn(null);

        $this->orderRepoMock->shouldReceive('find')->once()->andReturn($dummyOrder);
        $this->orderRepoMock->shouldReceive('save')->once()->andReturn($dummyOrder);

        $service = new \mamaprint\application\services\OrderService($this->orderRepoMock);

        $order = $service->completeOrder(1);

        $this->assertInstanceOf('Order\Order', $order, "Not an Order");
        $this->assertEquals($dummyOrder->id, $order->id);
        $this->assertEquals(Order::STATUS_COMPLETE, $order->status);

    }
}