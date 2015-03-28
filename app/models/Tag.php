<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 11.01.2015
 * Time: 21:24
 */
use \Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

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

    public static function getExisting($type)
    {
        $objects = DB::select("SELECT * FROM tags INNER JOIN taggables ON tags.id=taggables.tag_id AND tags.type='$type' AND taggables.taggable_type LIKE 'Catalog\\\CatalogItem' ESCAPE '|'  GROUP BY tags.id ORDER BY tags.weight ASC");
        if (!is_array($objects)) {
            $objects = [];
        }
        $tags = array_map(function ($obj) {
            $tag = new Tag();
            $tag->id = $obj->id;
            $tag->tag = $obj->tag;
            $tag->type = $obj->type;
            $tag->weight = $obj->weight;
            return $tag;
        }, $objects);
        return new Collection($tags);
    }

}