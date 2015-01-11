<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 23.12.2014
 * Time: 21:51
 */

namespace Catalog;

use Eloquent;
use Illuminate\Support\Facades\Auth;

class CatalogItem extends Eloquent
{
    protected $table = 'catalog_items';

    public function getPrice($value)
    {
        return (int)$value;
    }

    public function getOrderPrice()
    {
        //TODO create price rules in orders package instead of do it here
        $user = Auth::user();
        if (!empty($user) && empty($user->guestid) && !empty($this->registered_price)) {
            return (int)$this->registered_price;
        }
        return (int)$this->price;
    }

    public function tags()
    {
        return $this->belongsToMany('Catalog\Tag', 'tag_catalog_item');
    }

    public function getTagsAsString($separator = ',')
    {
        if ($this->tags->isEmpty()) {
            return '';
        }

        $names = [];
        foreach ($this->tags as $tag) {
            $names[] = $tag->tag;
        }
        return implode($separator, $names);
    }

}