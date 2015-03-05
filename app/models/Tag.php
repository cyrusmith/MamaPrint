<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 11.01.2015
 * Time: 21:24
 */
class Tag extends Eloquent
{

    const TYPE_TAG = 'tag';
    const TYPE_AGE = 'age';
    const TYPE_GOAL = 'goal';

    protected $table = 'tags';

    public $timestamps = false;

    public function catalogItems()
    {
        return $this->morphedByMany('Catalog\CatalogItem', 'taggable');
    }

    public static function boot()
    {
        parent::boot();

        Tag::deleting(function ($tag) {
            foreach ($tag->catalogItems as $catItem) {
                $catItem->tags()->detach($tag->id);
            }
        });
    }

}