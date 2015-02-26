<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 26.02.2015
 * Time: 10:31
 */

namespace Admin;

use Illuminate\Support\Facades\View;
use Info\InfoAges;

class AdminSeedController extends AdminController
{

    public function getSeeders()
    {
        return View::make("admin.seeders");
    }

    public function postInfos()
    {

        DB::transaction(function () {

            $ages = InfoAges::get();
            if (empty($ages) || $ages->isEmpty()) {

                DB::table('info_ages')->insert(
                    array('id' => InfoAges::BABY, 'title' => 'малыш'),
                    array('id' => InfoAges::KINDERGARDEN, 'title' => 'десткий сад'),
                    array('id' => InfoAges::PRESCHOOL, 'title' => 'дошкольник'),
                    array('id' => InfoAges::FIRST, 'title' => '1 класс'),
                    array('id' => InfoAges::SECOND, 'title' => '2 класс'),
                    array('id' => InfoAges::THIRD, 'title' => '3 класс'),
                    array('id' => InfoAges::FOURTH, 'title' => '4 класс')
                );

                $titleIdMap = [
                    'малыш' => InfoAges::BABY,
                    'десткий сад' => InfoAges::KINDERGARDEN,
                    'дошкольник' => InfoAges::PRESCHOOL,
                    '1 класс' => InfoAges::FIRST,
                    '2 класс' => InfoAges::SECOND,
                    '3 класс' => InfoAges::THIRD,
                    '4 класс' => InfoAges::FOURTH,
                ];

                $items = CatalogItem::get();

                $manyToManyData = [];
                foreach ($items as $item) {
                    if (!array_key_exists($item->info_age, $titleIdMap)) throw new \Exception($item->info_age . " is unknown");
                    $manyToManyData[] = [
                        'catalog_item_id' => $item->id,
                        'info_age_id' => $titleIdMap[$item->info_age]
                    ];
                }

                DB::table('info_ages_catalog_item')->insert($manyToManyData);

            }

        });

        return View::make("admin.seeders");
    }

}