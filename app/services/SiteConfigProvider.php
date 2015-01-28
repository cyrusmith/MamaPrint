<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 28.01.2015
 * Time: 11:18
 */
class SiteConfigProvider
{

    private $initialized = false;
    private $siteConfig = null;

    public function init()
    {
        if ($this->initialized) return;
        $this->siteConfig = SiteConfig::load();
    }

    public function getSiteConfig()
    {
        return $this->siteConfig;
    }

}