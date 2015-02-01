<?php

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
        $tmpUsersIds = [];
        if (!empty($guestId)) {

            if (empty($authUser->cart) || $authUser->cart->items->isEmpty()) {

                $tmpUser = User::where('guestid', '=', $guestId)->first();
                if (!empty($tmpUser)) {

                    $tmpUsersIds[] = $tmpUser->id;

                    foreach ($tmpUser->orders as $order) {
                        $guestOrders[] = $order;
                    }

                    $tmpCart = $tmpUser->cart;
                    if (!empty($tmpCart) && !$tmpCart->items->isEmpty()) {
                        try {
                            DB::beginTransaction();
                            $authUserCart = $authUser->getOrCreateCart();
                            foreach ($tmpUser->cart->items as $tmpCartItem) {
                                $cartItem = new \Cart\CartItem();
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
            if (!empty($link)) {
                $guestOrders[] = $link->order;
                $tmpUsersIds[] = $link->order->user->id;
            }
            Cookie::queue('download_token', null, 0);
        }

        foreach ($guestOrders as $order) {
            $order->user()->associate($authUser);
            $order->save();
        }

        DB::table('user_catalog_items_access')
            ->whereIn('user_id', $tmpUsersIds)
            ->update(['user_id' => $authUser->id]);

    }

}