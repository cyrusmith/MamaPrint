<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 27.01.2015
 * Time: 17:05
 */

namespace Admin;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class AdminSettingsController extends AdminController
{

    public function edit()
    {
        if (Request::isMethod('get')) {
            $config = \SiteConfig::load();
            $this->addToolbarAction("save", Lang::get("static.admin.save"), "settings", "post");
            $this->setPageTitle(Lang::get('static.admin.settings'));
            return $this->makeView('admin.settings', [
                'config' => $config
            ]);
        } elseif (Request::isMethod('post')) {

            $minOrderPrice = Input::get(\SiteConfig::MIN_ORDER_PRICE);
            $descriptor = Input::get(\SiteConfig::DESCRIPTOR);
            $seoDescription = Input::get(\SiteConfig::SEO_DESCRIPTION);

            $siteConfig = \SiteConfig::load();
            $siteConfig->setMinOrderPrice($minOrderPrice);
            $siteConfig->setDescriptor($descriptor);
            $siteConfig->setSeoDescription($seoDescription);
            $siteConfig->save();

            return Redirect::to(URL::action('Admin\AdminSettingsController@edit'));

        }

    }

}