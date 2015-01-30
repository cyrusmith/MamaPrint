<?php

class DownloadLink extends Eloquent
{

    protected $table = 'download_links';

    public function order()
    {
        return $this->belongsTo('Order\Order');
    }

    public function getDates()
    {
        return array('created_at');
    }

}