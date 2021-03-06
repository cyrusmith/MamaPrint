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

    public function getArticles()
    {
        $articles = Article::where('active', '=', true)->where('isblog', '=', true)->orderBy('publish_date', 'desc')->paginate(20);
        return View::make('articles.index', [
            'articles' => $articles,
            'page_title' => 'Статьи на сайте mama-print.ru',
            'page_description' => 'Блог с интересной информацией о материалах для развития детей',

        ]);
    }

    public function getArticle($path)
    {

        $article = Article::where('urlpath', '=', $path)->where('active', '=', true)->first();

        if (empty($article)) {
            if (Auth::check() && Auth::user()->hasRole(Role::getByName(Role::ROLE_ADMIN))) {
                $article = Article::where('urlpath', '=', $path)->first();
            }
        }
        if (empty($article)) {
            App::abort(404, Lang::get('messages.error.article_not_exist'));
        }

        return View::make('articles.article', [
            'article' => $article,
            'page_title' => empty($article->seo_title) ? $article->title : $article->seo_title,
            'page_description' => empty($article->seo_description) ? $article->description : $article->seo_description,
        ]);

    }

    public function getStaticArticle($path)
    {

        $article = Article::where('urlpath', '=', $path)->where('active', '=', true)->first();

        if (empty($article)) {
            if (Auth::check() && Auth::user()->hasRole(Role::getByName(Role::ROLE_ADMIN))) {
                $article = Article::where('urlpath', '=', $path)->first();
            }
        }
        if (empty($article)) {
            App::abort(404, Lang::get('messages.error.article_not_exist'));
        }

        return View::make('page',
            [
                'article' => $article,
                'page_title' => empty($article->seo_title) ? $article->title : $article->seo_title,
                'page_description' => $article->seo_description
            ]);

    }

}
