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
        return View::make('catalog.item', [
            'item' => $item
        ]);
    }

}