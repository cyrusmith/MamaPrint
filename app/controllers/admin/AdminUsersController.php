<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 29.01.2015
 * Time: 18:27
 */

namespace Admin;


use Illuminate\Support\Facades\Input;

class AdminUsersController extends AdminController
{

    public function getUsers()
    {
        $this->setPageTitle('Пользователи');
        $query = \User::orderBy('name', 'desc')->where('guestid', '=', null);

        $search = Input::get('search');
        if (mb_strlen($search) > 2) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('name', 'LIKE', "%$search%")
                    ->orWhere('email', 'LIKE', "%$search%");
            });
        } else {
            $search = '';
        }
        $users = $query->paginate(20);
        return $this->makeView('admin.users', [
            'users' => $users,
            'search' => $search
        ]);
    }

    public function getUserOrders($userId)
    {
        $user = \User::find($userId);
        if (empty($user)) {
            App::abort(404, 'Пользователь не найден');
        }
        $orders = $user->orders()->orderBy('created_at', 'desc')->paginate(50);
        $this->setPageTitle('Заказы пользователя ' . $user->name . ' (' . $user->email . ')');
        return $this->makeView('admin.userorders', [
            'orders' => $orders,
            'user' => $user
        ]);
    }

}