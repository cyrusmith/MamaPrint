<?php

use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Lang;
use \Illuminate\Support\Facades\Input;
use \Illuminate\Support\Facades\URL;
use Catalog\CatalogItem;
use Cart\Cart;

class CartController extends BaseController
{

    public function items()
    {
        $user = App::make("UsersService")->getUser();
        if (empty($user)) {
            return Response::json([
                'message' => Lang::get('messages.error.usernotfound'),
                '_links' => [
                ]
            ], 400);
        }

        $cart = $user->getOrCreateCart();

        $items = [];
        $total = 0;
        foreach ($cart->items as $item) {
            $gallery = $item->catalogItem->galleries()->first();
            $price = $item->catalogItem->getOrderPrice();
            $items[] = [
                'title' => $item->catalogItem->title,
                'price' => $price,
                'thumb' => '/images/' . $gallery->images()->first()->id,
                '_link' => [
                    'self' => URL::action('CartController@viewItem'),
                    'cart.delete' => URL::action('CartController@deleteItem',
                        [
                            'itemId' => $item->id
                        ])
                ]
            ];
            $total += $price;
        }

        return Response::json([
            'count' => $cart->items->count(),
            'total' => $total,
            'items' => $items,
            '_links' => [
                'cart.add' => URL::action('CartController@addItem')
            ]
        ], 200);

    }

    public function viewItem($itemId)
    {

        $user = App::make("UsersService")->getUser();
        if (empty($user)) {
            return Response::json([
                'message' => Lang::get('messages.error.usernotfound'),
                '_links' => [
                ]
            ], 400);
        }

        $cart = $user->getOrCreateCart();

        $cartItem = $cart->items()->find($itemId);

        if (empty($cartItem)) {
            return Response::json([
                'message' => Lang::get('messages.error.cart.itemnotfound'),
                '_links' => [
                ]
            ], 404);
        }

        $catalogItem = $cartItem->catalogItem;

        $data = $catalogItem->toJson();

        $data['_link'] = [
            'self' => URL::action('CartController@viewItem', [
                'itemId' => $itemId
            ])
        ];

        return Response::json($data, 200);
    }

    public function addItem()
    {

        $data = Input::get();
        $itemId = intval($data['id']);

        $item = CatalogItem::find($itemId);
        if (empty($item)) {
            return Response::json([
                'message' => Lang::get('messages.error.catalogitemnotfound'),
                '_links' => [
                    'self' => URL::action('CartController@addItem')
                ]
            ], 400);
        }

        $user = App::make("UsersService")->getUser();
        if (empty($user)) {
            return Response::json([
                'message' => Lang::get('messages.error.usernotfound'),
                '_links' => [
                    'self' => URL::action('CartController@addItem')
                ]
            ], 400);
        }

        try {
            $cart = $user->getOrCreateCart();
            $item = $cart->addCartItem($item);
            return Response::json([
                '_links' => [
                    'self' => URL::action('CartController@addItem')
                ]
            ], 200);
        } catch (Exception $e) {
            return Response::json([
                'message' => $e->getMessage(),
                '_links' => [
                    'self' => URL::action('CartController@addItem')
                ]
            ], 500);
        }

    }

    public function deleteItem($itemId)
    {

    }

}