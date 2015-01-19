<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 19.01.2015
 * Time: 12:52
 */
use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Lang;
use \Illuminate\Support\Facades\Input;
use \Illuminate\Support\Facades\URL;
use Catalog\CatalogItem;

class CartController extends BaseController
{

    public function addItem()
    {

        $data = Input::get();
        $itemId = intval($data['item_id']);

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
    }

}