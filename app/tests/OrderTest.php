<?php

use Order\Order;

use Illuminate\Support\Facades\DB;
use mamaprint\application\services\OrderService;
use mamaprint\infrastructure\events\Events;

class OrderTest extends TestCase
{

    /**
     * @throws Exception
     * @throws \mamaprint\application\services\Exception
     */
    public function testCompleteOrder()
    {

        $dummyOrder = new Order();
        $dummyOrder->id = 1;
        $dummyOrder->total = 1205;
        $dummyOrder->user_id = 2;

        Events::shouldReceive('fire')->andReturn(null);

        DB::shouldReceive('beginTransaction')->andReturn(null);
        DB::shouldReceive('commit')->andReturn(null);
        DB::shouldReceive('rollback')->andReturn(null);

        $this->orderRepoMock = Mockery::mock('mamaprint\domain\order\OrderRepositoryInterface');
        $this->orderRepoMock->shouldReceive('find')->once()->andReturn($dummyOrder);
        $this->orderRepoMock->shouldReceive('save')->once()->andReturn($dummyOrder);

        $service = new OrderService($this->orderRepoMock);

        $order = $service->completeOrder(1);

        $this->assertInstanceOf('Order\Order', $order, "Not an Order");
        $this->assertEquals($dummyOrder->id, $order->id);
        $this->assertEquals(Order::STATUS_COMPLETE, $order->status);

    }
}