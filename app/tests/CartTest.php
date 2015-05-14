<?php

use Illuminate\Support\Facades\App;
use mamaprint\domain\order\OrderCompleteEvent;
use mamaprint\infrastructure\events\Events;
use mamaprint\application\services\UserService;

use Illuminate\Support\Facades\DB;

use Order\Order;
use User\User;

use Cart\CartItem;

class CartTest extends TestCase
{

    function __construct()
    {
        $this->useDb = true;
    }

    public function testClearCartFail()
    {
        $user = new User();
        $user->save();
        $user->getOrCreateCart();

        $user->cart->items()->save(new CartItem());

        $order = new Order();
        $order->status = Order::STATUS_PENDING;
        $order->user_id = $user->id;
        $order->save();
        App::make("UserService")->clearCart(new OrderCompleteEvent($order->id));

        $user = User::find($user->id);
        $this->assertTrue(!$user->cart->items->isEmpty());
    }

    public function testClearCartOk()
    {
        $user = new User();
        $user->save();
        $user->getOrCreateCart();

        $user->cart->items()->save(new CartItem());

        $order = new Order();
        $order->status = Order::STATUS_COMPLETE;
        $order->user_id = $user->id;
        $order->save();
        App::make("UserService")->clearCart(new OrderCompleteEvent($order->id));

        $user = User::find($user->id);
        $user->getOrCreateCart();
        $this->assertTrue($user->cart->items->isEmpty());
    }

}