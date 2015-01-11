<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 05.01.2015
 * Time: 12:24
 */

namespace Admin;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Catalog\CatalogItem;
use Illuminate\Support\Facades\Redirect;

class AdminCatalogController extends AdminController
{

    public function index()
    {

        $items = CatalogItem::paginate(10);

        $this->addToolbarAction('add', 'Новый', 'catalog/add');
        return $this->makeView("admin.catalog.index", [
            'items' => $items
        ]);
    }

    public function add()
    {
        $this->setPageTitle(Lang::get('static.admin.pagetitle.catalog'));
        $this->addToolbarAction('save', 'Сохранить', 'catalog/save', 'post');
        $this->addToolbarAction('cancel', 'Отмена', 'catalog');
        return $this->makeView("admin.catalog.save");
    }

    public function edit($id)
    {

        $item = CatalogItem::find($id);

        if (empty($item)) {
            App::abort(404);
        }

        $this->setPageTitle(Lang::get('static.admin.pagetitle.catalog'));
        $this->addToolbarAction('save', 'Сохранить', 'catalog/save', 'post');
        $this->addToolbarAction('cancel', 'Отмена', 'catalog');
        return $this->makeView("admin.catalog.save", [
            'data' => $item->toArray()
        ]);
    }

    public function save()
    {
        return Redirect::to('/admin/catalog');
    }

}