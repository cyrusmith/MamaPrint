<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 05.01.2015
 * Time: 12:24
 */

namespace Admin;


class AdminCatalogController extends BaseController
{

    public function index()
    {
        return View::make("admin.catalog.index");
    }

}