<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 11.01.2015
 * Time: 21:24
 */

namespace Catalog;

use Eloquent;

class Tag extends Eloquent
{

    protected $table = 'tags';

    public $timestamps = false;

    public function catalogItems()
    {
        return $this->belongsToMany('Catalog\CatalogItem', 'tag_catalog_item');
    }

}