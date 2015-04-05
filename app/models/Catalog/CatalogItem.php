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
use Tag;

class CatalogItem extends Eloquent
{
    protected $table = 'catalog_items';

    public function getPrice($value)
    {
        return (int)$value;
    }

    /**
     * @deprecate
     * @return int
     */
    public function getOrderPrice()
    {
        $user = Auth::user();
        if (!empty($user) && !$user->isGuest()) {
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

    public function taggableTags()
    {
        return $this->morphToMany('Tag', 'taggable');
    }


    public function tags()
    {
        $relation = $this->morphToMany('Tag', 'taggable');
        $query = $relation->getQuery();
        $query->where('type', '=', Tag::TYPE_TAG);
        return $relation;
    }

    public function ages()
    {
        $relation = $this->morphToMany('Tag', 'taggable');
        $query = $relation->getQuery();
        $query->where('type', '=', Tag::TYPE_AGE);
        return $relation;
    }

    public function goals()
    {
        $relation = $this->morphToMany('Tag', 'taggable');
        $query = $relation->getQuery();
        $query->where('type', '=', Tag::TYPE_GOAL);
        return $relation;
    }

    public function agesAsCommaDelimeted()
    {
        $names = [];
        foreach ($this->ages as $age) {
            $names[] = $age->tag;
        }
        return implode(",", $names);
    }

    public function tagsAsCommaDelimeted()
    {
        $names = [];
        foreach ($this->tags as $age) {
            $names[] = $age->tag;
        }
        return implode(",", $names);
    }

    public function goalsAsCommaDelimeted()
    {
        $names = [];
        foreach ($this->goals as $age) {
            $names[] = $age->tag;
        }
        return implode(",", $names);
    }

    public function updateTags($values)
    {
        $this->updateTagsWithType($values, Tag::TYPE_TAG);
    }

    public function updateAges($values)
    {
        $this->updateTagsWithType($values, Tag::TYPE_AGE);
    }

    public function updateGoals($values)
    {
        $this->updateTagsWithType($values, Tag::TYPE_GOAL);
    }

    private function updateTagsWithType($values, $type)
    {
        try {

            DB::beginTransaction();

            $idObjs = DB::select('select tags.id from taggables, tags  where taggables.tag_id = tags.id and tags.type=?', array($type));

            $ids = [];
            foreach ($idObjs as $obj) {
                $ids[] = $obj->id;
            }

            if (count($ids) > 0) {
                $this->taggableTags()->detach($ids);
            }

            $tags = [];
            $tagIds = [];
            foreach ($values as $val) {
                $tag = Tag::whereTag($val)->where('type', '=', $type)->first();
                if (empty($tag)) {
                    $tag = new Tag;
                    $tag->tag = $val;
                    $tag->type = $type;
                    $tag->save();
                }
                $tags[] = $tag;
                $tagIds[] = $tag->id;
            }
            $this->taggableTags()->attach($tagIds);
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