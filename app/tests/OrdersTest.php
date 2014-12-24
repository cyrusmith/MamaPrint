<?php

use Order\Order;
use Catalog\CatalogItem;

class OrdersTest extends TestCase
{

    private $user;

    public function setUp()
    {
        parent::setUp();

        $item = new CatalogItem;
        $item->title = 'Item1';
        $item->price = 10000;
        $item->save();

        $user = new User;

        $user->name = "John";
        $user->email = "john@mail.ru";
        $user->password = "123";

        $user->save();

        $this->be($user);

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

        $items = $order->items();

        $this->assertTrue(!empty($items));
        $item = $items->first();
        $this->assertEquals($item->id, 1);
        $this->assertEquals($item->price, 10000);

        $this->assertRedirectedTo('/pay/1');

    }

}
