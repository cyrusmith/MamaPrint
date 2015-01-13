<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 13.01.2015
 * Time: 12:26
 */
class Attachment extends Eloquent
{

    const MODEL_CATALOGITEM = 'catalogitem';
    const MODEL_ARTICLE = 'article';

    protected $table = 'attachments';

}