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
        try {
            DB::beginTransaction();
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
                            if ($order->status == \Order\Order::STATUS_COMPLETE) {
                                $guestOrders[] = $order;
                            }
                        }

                        $tmpCart = $tmpUser->cart;
                        if (!empty($tmpCart) && !$tmpCart->items->isEmpty()) {

                            $authUserCart = $authUser->getOrCreateCart();
                            foreach ($tmpUser->cart->items as $tmpCartItem) {
                                $cartItem = new \Cart\CartItem();
                                if ($tmpCartItem->catalogItem->getOrderPrice() > 0) {
                                    $cartItem->catalogItem()->associate($tmpCartItem->catalogItem);
                                    $authUserCart->items()->save($cartItem);
                                }
                            }
                            $tmpUser->cart->delete();

                        }

                    }

                }

            }

            $downloadToken = Cookie::get('download_token');

            if (!empty($downloadToken)) {
                $link = DownloadLink::where('token', '=', $downloadToken)->first();
                if (!empty($link)) {
                    $guestOrders[] = $link->order;
                }
                Cookie::queue('download_token', null, 0);
            }

            $guestCatalogItems = [];
            foreach ($guestOrders as $order) {
                $order->user()->associate($authUser);
                $order->save();
                foreach ($order->items as $orderItem) {
                    $guestCatalogItems[] = $orderItem->catalogItem;
                }
            }

            $guestCatalogItems = $this->uniqueArrayOfCatalogItems($guestCatalogItems);

            $authUser->attachCatalogItems($guestCatalogItems);

            DB::commit();
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error($e);
            DB::rollback();
        }

    }

    private function uniqueArrayOfCatalogItems($arr)
    {
        $len = count($arr);
        $unique = [];
        if ($len > 0) {
            $unique[] = $arr[0];
            for ($i = 1; $i < $len; $i++) {
                $uniqueLen = count($unique);
                $j = 0;
                for (; $j < $uniqueLen; $j++) {
                    if ($unique[$j]->id == $arr[$i]->id) {
                        break;
                    }
                }
                if ($j === $uniqueLen) {
                    $unique[] = $arr[$i];
                }
            }
        }
        return $unique;
    }

}