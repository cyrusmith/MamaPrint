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
use Illuminate\Support\Facades\DB;

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

    public function updateTags($tagTags)
    {
        try {
            DB::beginTransaction();
            $this->tags()->detach();;
            $tags = [];
            foreach ($tagTags as $tagTag) {
                $tag = Tag::whereTag($tagTag)->first();
                if (empty($tag)) {
                    $tag = new Tag;
                    $tag->tag = $tagTag;
                    $tag->save();
                }
                $tags[] = $tag;
            }

            $this->tags()->saveMany($tags);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

    }

}