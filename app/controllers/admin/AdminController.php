<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 07.01.2015
 * Time: 11:08
 */

namespace Admin;


use Illuminate\Support\Facades\View;

class AdminController extends \BaseController
{

    private $actions = [];
    private $pageTitle = null;

    protected function addToolbarAction($type, $title, $url, $method = 'get')
    {
        $this->actions[] = [
            'type' => $type,
            'title' => $title,
            'url' => $url,
            'method' => $method,
        ];
    }

    protected function setPageTitle($title)
    {
        $this->pageTitle = $title;
    }

    protected function makeView($view, $data = null)
    {
        if (!is_array($data) || empty($data)) {
            $data = [];
        }
        $data['toolbaractions'] = $this->actions;
        $data['pagetitle'] = $this->pageTitle;
        return View::make($view, $data);
    }

}