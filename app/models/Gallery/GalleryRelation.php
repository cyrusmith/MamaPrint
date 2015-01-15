<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 15.01.2015
 * Time: 11:47
 */

namespace Gallery;


use Catalog\CatalogItem;
use Eloquent;
use Illuminate\Support\Facades\DB;

class GalleryRelation extends Eloquent
{

    const MODEL_CATALOGITEM = 'catalogitem';
    const MODEL_ARTICLE = 'article';

    protected $table = 'gallery_relations';
    public $timestamps = false;

    public static function createRelation($model, $gallery)
    {

        if (empty($model)) {
            throw new Exception("Model is empty");
        }

        if (empty($gallery)) {
            throw new Exception("Gallery is empty");
        }

        if (empty($gallery->id)) {
            throw new Exception("Gallery id is empty");
        }

        $modelName = null;

        if ($model instanceof CatalogItem) {
            $modelName = self::MODEL_CATALOGITEM;
        }

        if (empty($modelName)) {
            throw new Exception("Model not supported");
        }

        try {
            DB::beginTransaction();
            $relation = GalleryRelation::where('model', '=', $modelName)->where('model_id', '=', $model->id)->first();
            if (!empty($relation)) return $relation;
            $relation = new GalleryRelation;
            $relation->model = $modelName;
            $relation->model_id = $model->id;
            $relation->gallery_id = $gallery->id;
            $relation->save();
            DB::commit();
            return $relation;
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

    }
}