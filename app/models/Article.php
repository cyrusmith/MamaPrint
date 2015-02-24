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

    public static function getArticleContent($path)
    {
        $article = Article::where('urlpath', '=', $path)->first();
        if (empty($article)) return null;
        return $article->content;
    }

}