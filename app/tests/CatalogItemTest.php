<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 26.12.2014
 * Time: 16:18
 */

use Catalog\CatalogItem;

class CatalogItemTest extends TestCase
{

    private $item;

    public function setUp()
    {
        parent::setUp();
        $this->item = new CatalogItem();
        $this->item->title = "Item1";
        $this->item->price = 10000;
        $this->item->registered_price = 7900;
        $this->item->asset_extension = 'pdf';
        $this->item->asset_name = 'winterbook';
        $this->item->slug = 'winterbook';
        $this->item->save();
    }

    public function testOrderPriceNoUser()
    {
        $item = CatalogItem::find($this->item->id);
        $this->assertEquals(10000, $item->getOrderPrice());
    }

    public function testOrderPriceGuestId()
    {
        $guestId = "123";
        $user = new User;
        $user->name = $guestId;
        $user->email = $guestId;
        $user->password = $guestId;
        $user->guestid = $guestId;
        $user->save();
        $this->be($user);
        $item = CatalogItem::find($this->item->id);
        $this->assertEquals(10000, $item->getOrderPrice());
    }

    public function testOrderPriceAuth()
    {
        $guestId = "123";
        $user = new User;
        $user->name = "Vasya";
        $user->email = "vasya@mail.ru";
        $user->password = "pass1";
        $user->save();
        $this->be($user);
        $item = CatalogItem::find($this->item->id);
        $this->assertEquals(7900, $item->getOrderPrice());
    }

    public function testOrderPriceAuthDefault()
    {
        $guestId = "123";
        $user = new User;
        $user->name = "Vasya";
        $user->email = "vasya@mail.ru";
        $user->password = "pass1";
        $user->save();
        $this->be($user);
        $item = CatalogItem::find($this->item->id);
        $item->registered_price = null;
        $item->save();
        $this->assertEquals(10000, $item->getOrderPrice());
    }

}