<?php

use Order\Order;

use Illuminate\Support\Facades\DB;
use mamaprint\infrastructure\events\AppEvent;
use \Illuminate\Support\Facades\Event;
use \Illuminate\Support\Facades\App;
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

        $EVENT_KEY = "some.event.key";

        $appEvent = Mockery::mock('mamaprint\infrastructure\events\AppEvent');
        $appEvent->shouldReceive('getKey')->andReturn($EVENT_KEY);

        $this->stdMock->shouldReceive('handleEvent')->once()->withArgs([$appEvent])->andReturn(null);

        Events::listen($appEvent, 'EventsTestService@handleEvent');

        Events::fire($appEvent);

    }
}