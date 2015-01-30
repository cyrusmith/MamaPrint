<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 24.12.2014
 * Time: 11:19
 */

use Account\Account;
use Account\OperationRefill;

class UsersService
{

    public function getUser()
    {
        if (Auth::check()) {
            return Auth::user();
        }
        $guestId = Session::get('guestid');
        if (!empty($guestId)) {
            return User::where('guestid', '=', $guestId)->first();
        } else {
            return null;
        }
    }

    public function moveInfoFromGuest()
    {
        if (!Auth::check()) throw new Exception('Пользователь не авторизован');

        $authUser = Auth::user();

        $guestId = Session::get('guestid');
        if (empty($guestId)) {
            $guestId = Cookie::get('guestid');
        }
        $guestOrders = [];
        if (!empty($guestId)) {

            if (empty($authUser->cart) || $authUser->cart->items->isEmpty()) {

                $tmpUser = User::where('guestid', '=', $guestId)->first();
                if (!empty($tmpUser)) {

                    foreach ($tmpUser->orders as $order) {
                        $guestOrders[] = $order;
                    }

                    $tmpCart = $tmpUser->cart;
                    if (!empty($tmpCart) && !$tmpCart->items->isEmpty()) {
                        try {
                            DB::beginTransaction();
                            $authUserCart = $authUser->getOrCreateCart();
                            foreach ($tmpUser->cart->items as $tmpCartItem) {
                                $cartItem = new CartItem;
                                $cartItem->catalogItem()->associate($tmpCartItem->catalogItem);
                                $authUserCart->items()->save($cartItem);
                            }
                            $tmpUser->cart->delete();
                            DB::commit();
                        } catch (Exception $e) {
                            \Illuminate\Support\Facades\Log::error($e);
                            DB::rollback();
                        }
                    }
                }

            }

        }

        $downloadToken = Cookie::get('download_token');

        if (!empty($downloadToken)) {
            $link = DownloadLink::where('token', '=', $downloadToken)->first();
            $guestOrders[] = $link->order;
        }

        foreach ($guestOrders as $order) {
            $order->user()->associate($authUser);
            $order->save(;
        }


    }

}