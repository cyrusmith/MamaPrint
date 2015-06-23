<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 05.02.2015
 * Time: 11:53
 */
class Article extends Eloquent
{

    protected $table = 'articles';

    public function getPagebreak()
    {
        $parts = explode("<p><!-- pagebreak --></p>", $this->content);
        return $parts[0];
    }

    public function getDates()
    {
        return array('created_at', 'updated_at', 'publish_date');
    }

    public function getImagePath() {
        $gallery = $this->galleries()->first();
        if(empty($gallery)) return null;
        $image =  $gallery->images()->first();
        if(empty($image)) return null;
        return URL::to('/')."/images/".$image->id.".".$image->extension;
    }

    public function galleries()
    {
        return $this->morphToMany('Gallery\Gallery', 'gallery_relation');
    }

    public static function getArticleContent($path)
    {
        $article = Article::where('urlpath', '=', $path)->first();
        if (empty($article)) return null;
        return $article->content;
    }

}