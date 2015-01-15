<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 15.01.2015
 * Time: 19:10
 */

use \Illuminate\Support\Facades\App;

class GalleryController extends BaseController
{

    public function view($id)
    {
        try {
            App::make('GalleryService')->outputImage($id, intval(Input::get('width')), intval(Input::get('height')), (boolean)intval(Input::get('crop')));
            App::abort(200);
        } catch (Exception $e) {
            if ($e->getCode()) {
                App::abort($e->getCode());
            } else {
                App::abort(500);
            }

        }

    }

    public function deleteImage($id)
    {

        try {
            App::make('GalleryService')->deleteImage($id);
            return Response::json(array(
                "status" => true,
                "id" => $id
            ), 200);
        } catch (Exception $e) {
            return Response::json(array(
                "status" => false,
                "error" => $e->getMessage()
            ), 400);

        }
    }

}