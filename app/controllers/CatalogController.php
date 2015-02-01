<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 16.01.2015
 * Time: 11:47
 */

use Catalog\CatalogItem;

class CatalogController extends BaseController
{

    public function index()
    {
        $items = CatalogItem::where('active', '=', true)->get();
        return View::make('catalog.index', [
            'items' => $items
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
            App::abort(404);
        }

        $gallery = $item->galleries()->first();
        $images = [];
        if (!empty($gallery) && !$gallery->images->isEmpty()) {
            $images = $gallery->images->all();
        }

        return View::make('catalog.item', [
            'item' => $item,
            'images' => $images
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