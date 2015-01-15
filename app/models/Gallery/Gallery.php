<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 15.01.2015
 * Time: 11:20
 */

namespace Gallery;


use Eloquent;

class Gallery extends Eloquent
{

    protected $table = 'galleries';

    public function images()
    {
        return $this->hasMany('Gallery\GalleryImage');
    }

}