<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 05.02.2015
 * Time: 10:43
 */

namespace Admin;


use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Article;

class AdminArticlesController extends AdminController
{

    public function getArticles()
    {

        $query = Article::where('title', '<>', '""');

        $search = Input::get('search');
        if (mb_strlen($search) > 2) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('title', 'LIKE', "%$search%")
                    ->orWhere('description', 'LIKE', "%$search%");
            });
        }

        $articles = $query->paginate(20);

        $this->setPageTitle(Lang::get('static.admin.pagetitle.catalog'));
        $this->addToolbarAction('add', Lang::get('static.admin.article.new'), 'articles/0');
        return $this->makeView("admin.articles.index", [
            'articles' => $articles,
            'search' => $search,
        ]);
    }

    public function getArticle($id)
    {
        $id = intval($id);

        $this->addToolbarAction('save', 'Сохранить', 'articles', 'post');
        $this->addToolbarAction('cancel', 'Отмена', 'articles');

        $article = [];
        if ($id > 0) {
            $article = Article::find($id);
            if (empty($article)) {
                App::abort(404, Lang::get('messsages.error.article_not_exist'));
            }
            $article = $article->toArray();
        }

        return $this->makeView("admin.articles.edit", $article);

    }

    public function postArticle()
    {
        $form = array(
            'id' => Input::get('id'),
            'title' => str_replace('"', '&quot;', Input::get('title')),
            'description' => str_replace('"', '&quot;', Input::get('description')),
            'content' => Input::get('content'),
            'seo_title' => str_replace('"', '&quot;', Input::get('seo_title')),
            'seo_description' => str_replace('"', '&quot;', Input::get('seo_description')),
            'active' => Input::get('active'),
            'publish_date' => Input::get('publish_date'),
            'urlpath' => $this->filterUrlpath(Input::get('urlpath')),
        );

        $id = intval($form['id']);

        $validator = Validator::make(
            $form,
            array(
                'title' => array('required'),
                'urlpath' => array('required'),
                'description' => array('required'),
                'content' => array('required'),
                'publish_date' => array('required'),
            )
        );

        if ($validator->fails()) {
            return Redirect::action('Admin\AdminArticlesController@getArticle', [
                'id' => $id
            ])->withErrors($validator)->with('form', $form);
        }

        if ($id > 0) {
            $article = Article::find($id);
        } else {
            $article = new Article;
        }

        $article->title = $form['title'];
        $article->description = $form['description'];
        $article->content = $form['content'];
        $article->seo_title = $form['seo_title'];
        $article->seo_description = $form['seo_description'];
        $article->urlpath = $form['urlpath'];
        $article->publish_date = $form['publish_date'];

        $article->save();

        return Redirect::action('Admin\AdminArticlesController@getArticle', [
            'id' => $article->id
        ]);

    }

    private function filterUrlpath($urlpath)
    {
        return mb_strtolower(trim(trim($urlpath), "/\\"));
    }

}