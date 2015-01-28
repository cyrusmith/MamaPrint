<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 27.01.2015
 * Time: 17:09
 */
class SiteConfig
{

    const MIN_ORDER_PRICE = 'min_order_price';

    private $minOrderPrice = 0;

    private function __construct()
    {
    }

    public function setMinOrderPrice($value)
    {
        $this->minOrderPrice = intval($value);
    }

    public function getMinOrderPrice()
    {
        return $this->minOrderPrice;
    }

    public static function load()
    {
        $configs = DB::table('site_config')->get();
        $data = [];
        for ($i = 0; $i < count($configs); $i++) {
            $data[$configs[$i]->name] = $configs[$i]->value;
        }

        $siteConfig = new SiteConfig();
        if (array_key_exists(self::MIN_ORDER_PRICE, $data)) {
            $siteConfig->setMinOrderPrice($data[self::MIN_ORDER_PRICE]);
        } else {
            DB::table('site_config')
                ->insert(
                    array("name" => self::MIN_ORDER_PRICE,
                        "value" => $siteConfig->getMinOrderPrice())
                );
        }

        return $siteConfig;
    }

    public function save()
    {
        DB::transaction(function () {
            DB::table('site_config')
                ->where('name', self::MIN_ORDER_PRICE)
                ->update(array('value' => strval($this->getMinOrderPrice())));
        });
    }

    public function toJSON()
    {
        $data = [
            "min_order_price" => $this->getMinOrderPrice()
        ];
        return json_encode($data);
    }

}