<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 05.02.2015
 * Time: 12:31
 */
use \Illuminate\Support\Facades\Lang;

class ArticlesController extends BaseController
{

    public function getArticles($path)
    {

        $article = Article::where('urlpath', '=', $path)->where('active', '=', true)->first();

        if (empty($article)) {
            App::abort(404, Lang::get('messages.error.article_not_exist'));
        }

        return View::make('articles.article', ['article' => $article]);

    }

    public function getArticle($path)
    {

        $article = Article::where('urlpath', '=', $path)->where('active', '=', true)->first();

        if (empty($article)) {
            App::abort(404, Lang::get('messages.error.article_not_exist'));
        }

        return View::make('articles.article', ['article' => $article]);

    }

}