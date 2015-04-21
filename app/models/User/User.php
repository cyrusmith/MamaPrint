<?php namespace User;

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Cart\Cart;
use Eloquent;
use Illuminate\Support\Facades\DB;

class User extends Eloquent implements UserInterface, RemindableInterface
{

    use UserTrait, RemindableTrait;

    const TYPE_VK = "vk";

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
    protected $hidden = array('email', 'phone', 'cart', 'accounts', 'catalog_items', 'created_at', 'updated_at', 'password', 'remember_token');

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

    public function hasRole($aRole)
    {
        foreach ($this->roles as $role) {
            if ($role->id == $aRole->id) {
                return true;
            }
        }
        return false;
    }

    public function cart()
    {
        return $this->hasOne('Cart\Cart');
    }

    public function catalogItems()
    {
        return $this->belongsToMany('Catalog\CatalogItem', 'user_catalog_items_access', 'user_id', 'catalog_item_id');
    }

    public function hasItem($catalogItem)
    {
        foreach ($this->catalogItems as $item) {
            if ($item->id == $catalogItem->id) {
                return true;
            }
        }
        return false;
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
            $this->cart = $cart;
        }
        return $cart;
    }

    /**
     * @deprecated - make it eventually consistent via UserService
     * @param $catalogItems
     * @throws Exception
     * @throws \Exception
     */
    public function attachCatalogItems($catalogItems)
    {

        if (empty($catalogItems) || count($catalogItems) == 0) return;

        $ids = [];
        foreach ($catalogItems as $item) {
            $ids[] = $item->id;
        }

        if (count($ids) == 0) return;

        try {
            DB::beginTransaction();
            $this->catalogItems()->detach($ids);
            $this->catalogItems()->attach($ids);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

    }


}
