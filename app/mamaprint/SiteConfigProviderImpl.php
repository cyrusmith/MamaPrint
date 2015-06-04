<?php
namespace mamaprint;
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 28.01.2015
 * Time: 11:18
 */
class SiteConfigProviderImpl implements SiteConfigProvider
{

    public function __construct() {
        $a = 1;
    }

    public function getSiteConfig()
    {
        if(empty($this->siteConfig))
            $this->siteConfig = \SiteConfig::load();
        return $this->siteConfig;
    }

}