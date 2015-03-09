<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 26.02.2015
 * Time: 10:31
 */

namespace Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Tag;
use Catalog\CatalogItem;

class AdminSeedController extends AdminController
{

    public function getSeeders()
    {
        return View::make("admin.seeders");
    }

    public function postTags()
    {

        DB::transaction(function () {


            DB::table('tags')->update([
                'type' => \Tag::TYPE_TAG
            ]);

            DB::table('taggables')->update([
                'taggable_type' => 'Catalog\CatalogItem'
            ]);

            //ages
            $ages = array_filter(array_map(function ($item) {
                return $item->info_age;
            }, DB::table('catalog_items')->select('info_age')->get()));
            $agesMap = [];
            foreach ($ages as $age) {
                if (array_key_exists($age, $agesMap)) continue;
                $ageTag = new Tag();
                $ageTag->type = \Tag::TYPE_AGE;
                $ageTag->tag = $age;
                $ageTag->save();
                $agesMap[$age] = $ageTag;
            }

            //goals
            $goals = array_filter(array_map(function ($item) {
                return $item->info_targets;
            }, DB::table('catalog_items')->select('info_targets')->get()));
            $goalsMap = [];
            foreach ($goals as $goal) {
                $goalItems = array_filter(array_map(function ($item) {
                    return trim($item);
                }, explode(",", $goal)));
                foreach ($goalItems as $goalItem) {
                    if (array_key_exists($goalItem, $goalsMap)) continue;
                    $goalTag = new Tag();
                    $goalTag->type = \Tag::TYPE_GOAL;
                    $goalTag->tag = $goalItem;
                    $goalTag->save();
                    $goalsMap[$goalItem] = $goalTag;
                }
            }

            $items = CatalogItem::all();
            foreach ($items as $item) {
                $ageInfo = $item->info_age;
                if (!empty($ageInfo)) {
                    $item->taggableTags()->attach($agesMap[$ageInfo]);
                }

                $infoTargets = array_filter(array_map(function ($item) {
                    return trim($item);
                }, explode(",", $item->info_targets)));
                foreach ($infoTargets as $infoTarget) {
                    if (!array_key_exists($infoTarget, $goalsMap)) {
                        throw new \Exception("goalsMap has no " . $infoTarget);
                    }
                    $item->taggableTags()->attach($goalsMap[$infoTarget]);
                }
            }
        });

        return View::make("admin.seeders");
    }

}