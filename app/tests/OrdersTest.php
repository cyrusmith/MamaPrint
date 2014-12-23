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

        $user = new User(array(
            'name' => 'John',
            'email' => 'john@mail.ru',
            'password' => 'pass1'
        ));

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

        $this->assertTrue($this->client->getResponse()->isOk());

        $order = Order::all()->first();

        $this->assertTrue(!empty($order));
        $this->assertTrue($order->id == 1);

        $items = $order->items();
        echo $items;
        $this->assertTrue(!empty($items));


        $this->assertRedirectedToAction('PaymentsController@pay');
    }

}
