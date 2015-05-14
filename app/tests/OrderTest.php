<?php

use Order\Order;

class OrderTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->orderRepoMock = Mockery::mock('mamaprint\repositories\OrderRepositoryInterface');
        App::instance('mamaprint\repositories\OrderRepositoryInterface', $this->orderRepoMock);
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

        DB::shouldReceive('beginTransaction')->once()->andReturn(null);
        DB::shouldReceive('commit')->once()->andReturn(null);
        DB::shouldReceive('rollback')->once()->andReturn(null);
        $this->orderRepoMock->shouldReceive('find')->once()->andReturn($dummyOrder);

        $order = App::make('OrderService')->completeOrder(1);

        $this->assertInstanceOf('Order\Order', $order, "Not an Order");
        $this->assertEquals($dummyOrder->id, $order->id);
        $this->assertEquals(Order::STATUS_COMPLETE, $order->status);


    }
}