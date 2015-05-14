<?php

use Illuminate\Support\Facades\App;
use mamaprint\domain\order\OrderCompleteEvent;
use mamaprint\infrastructure\events\Events;

class EventsTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->stdMock = Mockery::mock('stdClass');
        App::instance('EventsTestService', $this->stdMock);
    }

    public function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testFireEvents()
    {

        $appEvent = new OrderCompleteEvent(1);
        $this->stdMock->shouldReceive('handleEvent')->once()->withArgs([$appEvent])->andReturn(null);

        Events::listen("OrderCompleteEvent", 'EventsTestService@handleEvent');

        Events::fire($appEvent);

    }
}