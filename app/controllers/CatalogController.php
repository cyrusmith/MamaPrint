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
        return View::make('main', [
            'items' => $items
        ]);
    }

}