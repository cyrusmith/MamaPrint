<?php

namespace mamaprint\application\services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use mamaprint\domain\user\UserRepositoryInterface;

class UserService
{

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
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

    public function clearCart($userId)
    {
        try {
            DB::beginTransaction();
            $user = $this->userRepository->find($userId);
            $user->cart->delete();
            DB::commit();
        }
        catch(\Exception $e) {
            DB::rollback();
            Log::error($e);
        }
    }

    public function attachItemsFromOrder($orderId)
    {

    }

}