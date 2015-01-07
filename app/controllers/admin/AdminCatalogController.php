<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 05.01.2015
 * Time: 12:24
 */

namespace Admin;


use Illuminate\Support\Facades\Lang;

class AdminCatalogController extends AdminController
{

    public function index()
    {
        $this->addToolbarAction('add', 'Новый', 'catalog/add');
        return $this->makeView("admin.catalog.index");
    }

    public function add()
    {
        $this->setPageTitle(Lang::get('static.admin.pagetitle.catalog'));
        $this->addToolbarAction('save', 'Сохранить', 'catalog/add', 'post');
        $this->addToolbarAction('cancel', 'Отмена', 'catalog');
        return $this->makeView("admin.catalog.save");
    }

}