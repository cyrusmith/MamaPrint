<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 23.12.2014
 * Time: 21:51
 */

namespace Catalog;

use Eloquent;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CatalogItem extends Eloquent
{
    protected $table = 'catalog_items';

    public function getPrice($value)
    {
        return (int)$value;
    }

    public function getOrderPrice()
    {
        $user = Auth::user();
        if (!empty($user) && !$user->isGuest() && !empty($this->registered_price)) {
            return (int)$this->registered_price;
        }
        return (int)$this->price;
    }

    public function canBuyInOneClick()
    {
        $siteConfig = App::make("SiteConfigProvider")->getSiteConfig();
        return $this->getOrderPrice() >= ($siteConfig->getMinOrderPrice() * 100);
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

    public function relatedItems()
    {
        return $this->belongsToMany('Catalog\CatalogItem', 'catalogitem_relations', 'owner_id', 'relation_id');
    }

    public function tags()
    {
        $relation = $this->morphToMany('Catalog\Tag', 'taggable');
        $query = $relation->getQuery();
        $query->where('type', '=', Tag::TYPE_TAG);
        return $relation;
    }

    public function updateTags($tagTags, $type)
    {
        try {
            DB::beginTransaction();
            $this->tags()->detach();
            $tags = [];
            foreach ($tagTags as $tagTag) {
                $tag = Tag::whereTag($tagTag)->first();
                if (empty($tag)) {
                    $tag = new Tag;
                    $tag->tag = $tagTag;
                    $tag->type = $type;
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

    public function galleries()
    {
        return $this->morphToMany('Gallery\Gallery', 'gallery_relation');
    }

    public function attachments()
    {
        return $this->morphToMany('Attachment', 'attachment_relation');
    }

}