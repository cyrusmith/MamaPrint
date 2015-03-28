<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 16.01.2015
 * Time: 11:47
 */

use Catalog\CatalogItem;
use \Illuminate\Support\Facades\Input;

class CatalogController extends BaseController
{

    public function index()
    {
        return View::make('catalog.index', [
            'items' => CatalogItem::orderBy('weight', 'desc')->where('active', '=', true)->paginate(20),
            'tags' => Tag::where('type', '=', Tag::TYPE_TAG)->orderBy('weight', 'asc')->get(),
            'ages' => Tag::where('type', '=', Tag::TYPE_AGE)->orderBy('weight', 'asc')->get(),
            'goals' => Tag::where('type', '=', Tag::TYPE_GOAL)->orderBy('weight', 'asc')->get(),
        ]);
    }

    public function search()
    {
        $search = Input::get('search');

        $query = CatalogItem::orderBy('weight', 'desc')->where('active', '=', true);
        $tags = array_filter(explode(",", Input::get('tags')));
        $ages = array_filter(explode(",", Input::get('ages')));
        $goals = array_filter(explode(",", Input::get('goals')));

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('title', 'LIKE', "%$search%")
                    ->orWhere('short_description', 'LIKE', "%$search%");
            });
        }

        $tags = array_merge($tags, $ages, $goals);

        if (!empty($tags)) {

            /*$ands = [];

            foreach ($tags as $tag) {
                $ands[] = "EXISTS (SELECT 1 FROM taggables WHERE taggables.taggable_id=catalog_items.id AND taggables.tag_id = $tag AND taggable_type LIKE 'Catalog\\\CatalogItem' ESCAPE '|')";
            }*/

            $taggedIds = DB::select("SELECT t.taggable_id FROM taggables as t WHERE t.tag_id IN (" . implode(", ", $tags) . ") AND t.taggable_type LIKE 'Catalog\\\CatalogItem' ESCAPE '|'");

            $taggedIds = array_map(function ($item) {
                return $item->taggable_id;
            }, $taggedIds);

            $query->whereIn('id', $taggedIds);
        }

        if (Request::ajax()) {
            return Response::json($query->get(), 200);
        } else {
            return View::make('catalog.index', [
                'items' => $query->paginate(20),
                'tags' => Tag::where('type', '=', Tag::TYPE_TAG)->orderBy('weight', 'asc')->get(),
                'ages' => Tag::where('type', '=', Tag::TYPE_AGE)->orderBy('weight', 'asc')->get(),
                'goals' => Tag::where('type', '=', Tag::TYPE_GOAL)->orderBy('weight', 'asc')->get(),
                'search' => $search,
                'selected_tags' => $tags,
            ]);
        }
    }

    public function getTags()
    {

        $query = Tag::where('type', '=', Tag::TYPE_TAG);

        $q = mb_strtolower(trim(\Illuminate\Support\Facades\Input::get('q')));
        if (!empty($q)) {
            $query->where('tag', 'LIKE', "%$q%");
        }

        return Response::json($query->get(), 200);
    }

    public function getAges()
    {

        $query = Tag::where('type', '=', Tag::TYPE_AGE);

        $q = mb_strtolower(trim(\Illuminate\Support\Facades\Input::get('q')));
        if (!empty($q)) {
            $query->where('tag', 'LIKE', "%$q%");
        }

        return Response::json($query->get(), 200);
    }

    public function getGoals()
    {

        $query = Tag::where('type', '=', Tag::TYPE_GOAL);

        $q = mb_strtolower(trim(\Illuminate\Support\Facades\Input::get('q')));
        if (!empty($q)) {
            $query->where('tag', 'LIKE', "%$q%");
        }

        return Response::json($query->get(), 200);
    }

    public function getItemsFree()
    {
        $items = CatalogItem::orderBy('weight', 'desc')->where('active', '=', true)->where('registered_price', '=', 0)->paginate(50);
        return View::make('catalog.index', [
            'items' => $items,
            'page_title' => 'Бесплатные материалы на сайте mama-print.ru',
            'tags' => Tag::where('type', '=', Tag::TYPE_TAG)->orderBy('weight', 'asc')->get(),
            'ages' => Tag::where('type', '=', Tag::TYPE_AGE)->orderBy('weight', 'asc')->get(),
            'goals' => Tag::where('type', '=', Tag::TYPE_GOAL)->orderBy('weight', 'asc')->get()
        ]);
    }

    public function item($path)
    {

        $parts = array_filter(explode("/", $path), function ($item) {
            $item = trim($item);
            return !empty($item);
        });

        if (count($parts) !== 1) {
            App::abort(404);
        }

        $slug = $parts[count($parts) - 1];

        $item = CatalogItem::where('slug', '=', $slug)->where('active', '=', true)->first();
        if (empty($item)) {
            if (Auth::check() && Auth::user()->hasRole(Role::getByName(Role::ROLE_ADMIN))) {
                $item = CatalogItem::where('slug', '=', $slug)->first();
            }
        }

        if (empty($item)) {
            App::abort(404);
        }

        $gallery = $item->galleries()->first();
        $images = [];
        if (!empty($gallery) && !$gallery->images->isEmpty()) {
            $images = $gallery->images->all();
        }

        return View::make('catalog.item', [
            'item' => $item,
            'images' => $images,
            'page_title' => $item->title,
            'page_description' => $item->short_description,
        ]);

    }

    public function getAttachments($path)
    {
        $parts = array_filter(explode("/", $path), function ($item) {
            $item = trim($item);
            return !empty($item);
        });

        if (count($parts) !== 1) {
            App::abort(404);
        }

        $slug = $parts[count($parts) - 1];

        $item = CatalogItem::where('slug', '=', $slug)->where('active', '=', true)->first();

        if (empty($item)) {
            App::abort(404);
        }

        $user = App::make('UsersService')->getUser();
        if (empty($user)) {
            App::abort(401, 'Нет доступа');
        }

        if (!$user->hasItem($item)) {
            if (!Auth::check() || $item->registered_price > 0) {
                App::abort(401, 'Нет доступа к материалу');
            }
        }

        $file = \Illuminate\Support\Facades\App::make("CatalogService")->getItemAttachmentPath($item->id);

        if (file_exists($file)) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            return Response::download($file, $item->title . " mama-print.ru." . $ext);
        } else {
            Log::error($file . ' does not exists');
            App::abort(404, 'Нет материалов для скачивания');
        }
    }

}