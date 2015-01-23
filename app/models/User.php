<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Cart\Cart;

class User extends Eloquent implements UserInterface, RemindableInterface
{

    use UserTrait, RemindableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('email', 'phone', 'created_at', 'updated_at', 'password', 'remember_token');

    protected $fillable = array('name', 'email', 'password');

    public function accounts()
    {
        return $this->hasMany('\Account\Account');
    }

    public function orders()
    {
        return $this->hasMany('\Order\Order');
    }

    public function roles()
    {
        return $this->belongsToMany('Role');
    }

    public function cart()
    {
        return $this->hasOne('Cart\Cart');
    }

    public function isGuest()
    {
        return !empty($this->guestid);
    }

    public function getRolesOrDefault()
    {
        if (!$this->roles->isEmpty()) {
            return $this->roles;
        }

        return Role::where('name', '=', Role::ROLE_USER);

    }

    public function getOrCreateCart()
    {
        $cart = $this->cart;
        if (empty($cart)) {
            $cart = new Cart;
            $this->cart()->save($cart);
        }
        return $cart;
    }

}
