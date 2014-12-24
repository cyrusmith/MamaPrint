<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 23.12.2014
 * Time: 21:51
 */

namespace Catalog;

use Eloquent;

class CatalogItem extends Eloquent
{
    protected $table = 'catalog_items';

    public function getPrice($value)
    {
        return (int)$value;
    }

}