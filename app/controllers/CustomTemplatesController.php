<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 29.01.2015
 * Time: 18:27
 */
class CustomTemplatesController extends \BaseController
{

    public function getGeneratorForm($id)
    {

        $tpl = CustomTemplate::find($id);

        if (empty($tpl)) {
            App::abort(404, 'Шаблон не существует');
        }

        return View::make('custom_templates.generator_form' ,[
            'id' => $tpl->id,
            'name' => $tpl->name,
            'colors' => explode(",", $tpl->colors),
            'page_title' => $tpl->name,
            'page_description' => 'Создайте свой материал для скачивания из шаблона &laquo;' . $tpl->name . '&raquo;',
        ]);

    }

    public function getImage($id)
    {

        $width = intval(Input::get('width'));
        $height = intval(Input::get('height'));

        $tpl = \CustomTemplate::find($id);
        if (empty($tpl) || empty($tpl->extension)) {
            App::abort(404);
        }
        $path = Config::get('mamaprint.custom_templates_dir') . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'original.' . $tpl->extension;
        if (!file_exists($path)) {
            App::abort(404);
        }

        $img = new \Imagick($path);

        if ($width > 0 || $height > 0) {
            $img->scaleImage($width, $height);
        }

        header('Content-Type: image/' . $img->getImageFormat());
        header('Cache-Control: max-age=86400');
        echo $img;
        App::abort(200);
    }

}