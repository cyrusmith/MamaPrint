<?php

use Order\Order;
use Catalog\CatalogItem;

class OrdersTest extends TestCase
{

    private $user;
    private $account;

    public function setUp()
    {
        parent::setUp();

        $item = new CatalogItem;
        $item->title = 'Item1';
        $item->price = 10000;
        $item->save();

        $this->user = new User;

        $this->user->name = "John";
        $this->user->email = "john@mail.ru";
        $this->user->password = "123";
        $this->user->save();
        $this->be($this->user);

        $this->account = new \Account\Account();
        $this->account->balance = 0;
        $this->account->currency = "RUR";
        $this->account->user()->associate($this->user);
        $this->account->save();

        $refill = new \Account\OperationRefill();
        $refill->amount = 12000;

        $this->account->addOperation($refill);
        $this->account->save();

        $this->assertEquals(12000, $this->account->balance);

    }


    public function testBuyItemNonExist()
    {
        $crawler = $this->client->request('POST', '/buyitem/2');
        $this->assertTrue($this->client->getResponse()->isNotFound());
    }

    public function testBuyItem()
    {
        $crawler = $this->client->request('POST', '/buyitem/1');

        $order = Order::all()->first();

        $this->assertTrue(!empty($order));
        $this->assertTrue($order->id == 1);
        $this->assertEquals($order->total, 10000);
        $this->assertEquals($order->status, Order::STATUS_PENDING);

        $items = $order->items();

        $this->assertTrue(!empty($items));
        $item = $items->first();
        $this->assertEquals($item->id, 1);
        $this->assertEquals($item->price, 10000);

        $this->assertRedirectedTo('/pay/1');

    }

    public function testServicePayOrder()
    {

        $crawler = $this->client->request('POST', '/buyitem/1');

        $orderService = new OrderService();

        $orderService->payOrder(1);

        $order = Order::find(1);

        $this->assertNotEmpty($order);
        $this->assertEquals($order->status, Order::STATUS_COMPLETE);

        $user = User::find($this->user->id);
        $account = $user->account;

        $this->assertEquals(2000, $account->balance);

        $purchase = $account->operations()->where('type', 'like', '%OperationPurchase')->first();
        $this->assertNotEmpty($purchase);
        $this->assertEquals($purchase->amount, 10000);

    }

}
