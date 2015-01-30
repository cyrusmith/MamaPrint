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
    const DESCRIPTOR = 'descriptor';

    private $descriptor = null;
    private $minOrderPrice = 0;

    private function __construct()
    {
    }

    public function setDescriptor($descriptor)
    {
        $this->descriptor = $descriptor;
    }

    public function getDescriptor()
    {
        return $this->descriptor;
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
        if (!empty($data)) {
            $siteConfig->setMinOrderPrice($data[self::MIN_ORDER_PRICE]);
            $siteConfig->setDescriptor($data[self::DESCRIPTOR]);
        } else {
            DB::table('site_config')
                ->insert(
                    [
                        ["name" => self::MIN_ORDER_PRICE,
                            "value" => $siteConfig->getMinOrderPrice()],
                        ["name" => self::DESCRIPTOR,
                            "value" => $siteConfig->getDescriptor()]
                    ]
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

            DB::table('site_config')
                ->where('name', self::DESCRIPTOR)
                ->update(array('value' => strval($this->getDescriptor())));
        });
    }

    public function toJSON()
    {
        $data = [
            "min_order_price" => $this->getMinOrderPrice(),
            "descriptor" => $this->getDescriptor()
        ];
        return json_encode($data);
    }

}