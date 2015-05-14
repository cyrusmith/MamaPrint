<?php

namespace mamaprint\application\services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use mamaprint\domain\order\OrderCompleteEvent;
use mamaprint\domain\order\OrderRepositoryInterface;
use mamaprint\domain\user\UserRepositoryInterface;
use Order\Order;

class UserService
{

    public function __construct(
        UserRepositoryInterface $userRepository,
        OrderRepositoryInterface $orderRepository)
    {
        $this->userRepository = $userRepository;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @deprecated
     */
    public function getUser()
    {
        if (Auth::check()) {
            return Auth::user();
        }
        $guestId = Session::get('guestid');
        if (!empty($guestId)) {
            return $this->userRepository->findGuest($guestId);
        } else {
            return null;
        }
    }

    public function clearCart(OrderCompleteEvent $orderCompleteEvent)
    {
        try {
            DB::beginTransaction();
            $orderId = $orderCompleteEvent->getOrderId();
            $order = $this->orderRepository->find($orderId);
            if ($order->status == Order::STATUS_COMPLETE) {
                $user = $this->userRepository->find($order->user_id);
                $user->cart->delete();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            throw $e;
        }
    }

    public function attachItemsFromOrder($orderId)
    {

    }

}