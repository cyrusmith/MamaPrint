<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 13.01.2015
 * Time: 12:26
 */

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Attachment extends Eloquent
{

    const MODEL_CATALOGITEM = 'catalogitem';
    const MODEL_ARTICLE = 'article';

    protected $table = 'attachments';

    public function scopeOfModel($query, $model)
    {
        return $query->whereModel($model);
    }

}