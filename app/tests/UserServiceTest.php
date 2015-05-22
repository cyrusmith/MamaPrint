<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 16.05.2015
 * Time: 17:04
 */

use Illuminate\Support\Facades\App;
use mamaprint\domain\order\OrderCompleteEvent;
use Order\Order;
use Order\OrderItem;
use User\User;

class UserServiceTest extends TestCase
{

    protected $useDb = true;

    public function setUp()
    {
        parent::setUp();
        $user = new User;
        $user->email = 'test@mail.ru';
        $user->name = 'test1';
        $user->password = '123';
        $user->save();
        $this->user = $user;
        $this->be($this->user);
    }

    public function testAttachItems()
    {
        $order = $this->createOrder(Order::STATUS_COMPLETE);
        App::make('UserService')->attachCatalogItems(new OrderCompleteEvent($order->id));
        $user = User::find($this->user->id);
        $userItems = $user->catalogItems();
        $userItems = $userItems->all();
        $this->assertEquals(2, count($userItems));
        $this->assertTrue($userItems[0]->id == 1 || $userItems[0]->id == 2);
        $this->assertTrue($userItems[1]->id == 1 || $userItems[1]->id == 2);
    }

    private function createOrder($status)
    {
        $order = new Order();
        $order->status = $status;
        $order->user_id = $this->user;
        $order->save();

        $orderItems = [];

        $oi = new OrderItem();
        $oi->order_id = $order->id;
        $oi->catalog_item_id = 1;
        $oi->price = 10;
        $orderItems[] = $oi;

        $oi = new OrderItem();
        $oi->order_id = $order->id;
        $oi->catalog_item_id = 2;
        $oi->price = 20;
        $orderItems[] = $oi;

        $order->items()->saveMany($orderItems);

        return $order;
    }

}