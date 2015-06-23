<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 05.02.2015
 * Time: 10:43
 */

namespace Admin;


use Gallery\Gallery;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Article;

class AdminArticlesController extends AdminController
{

    public function __construct(\GalleryService $galleryService) {
        $this->galleryService = $galleryService;
    }

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
            $image = $article->getImagePath();
            $article = $article->toArray();
            $article['image'] = $image;
        }

        return $this->makeView("admin.articles.edit", $article);

    }

    public function postArticle()
    {

        try {
            DB::beginTransaction();

            $form = array(
                'id' => Input::get('id'),
                'title' => str_replace('"', '&quot;', Input::get('title')),
                'description' => str_replace('"', '&quot;', Input::get('description')),
                'content' => Input::get('content'),
                'seo_title' => str_replace('"', '&quot;', Input::get('seo_title')),
                'seo_description' => str_replace('"', '&quot;', Input::get('seo_description')),
                'active' => Input::get('active'),
                'isblog' => Input::get('isblog'),
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
                $form['image'] = $article->getImagePath();
            } else {
                $article = new Article;
            }

            if (preg_match("#^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$#", $form['publish_date'])) {
                $form['publish_date'] .= ":00";
            }

            $article->title = $form['title'];
            $article->description = $form['description'];
            $article->content = $form['content'];
            $article->seo_title = $form['seo_title'];
            $article->seo_description = $form['seo_description'];
            $article->urlpath = $form['urlpath'];
            $article->publish_date = $form['publish_date'];
            $article->active = $form['active'] == "1";
            $article->isblog = $form['isblog'] == "1";

            $article->save();

            $id = $article->id;

            $files = Input::file();
            if (array_key_exists('image', $files) && !empty($files['image'])) {

                $gallery = $article->galleries()->first();
                if (empty($gallery)) {
                    $gallery = new Gallery();
                    $article->galleries()->save($gallery);
                }
                else {
                    foreach($gallery->images()->get() as $image) {
                        $this->galleryService->deleteImage($image->id);
                    }
                }

                $this->galleryService->saveImage($gallery, $files['image']);
            }
            DB::commit();
            return Redirect::action('Admin\AdminArticlesController@getArticle', ['id' => $article->id]);

        } catch (Exception $e) {
            DB::rollback();
            $messages[] = $e->getMessage();
        }
        return Redirect::action('Admin\AdminArticlesController@getArticle', ['id' => $id]);

    }

    public function deleteArticle($id)
    {
        Article::find($id)->delete();
        return Redirect::action('Admin\AdminArticlesController@getArticles');
    }

    private
    function filterUrlpath($urlpath)
    {
        return mb_strtolower(trim(trim($urlpath), "/\\"));
    }


}