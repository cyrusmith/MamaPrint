<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 29.01.2015
 * Time: 18:27
 */

namespace Admin;


use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

class CustomTemplatesController extends AdminController
{

    public function getTemplates()
    {
        $this->addToolbarAction('add', 'Новый', 'customtemplates/0');

        $this->setPageTitle('Шаблоны');
        $templates = \CustomTemplate::paginate(100);
        return $this->makeView('admin.custom_templates.index', [
            'templates' => $templates
        ]);
    }

    public function getTemplate($id)
    {
        $this->addToolbarAction('save', 'Сохранить', 'customtemplates', 'post');
        $id = intval($id);
        $data = [];
        if ($id) {
            $template = \CustomTemplate::find($id);
            if (empty($template)) {
                App::abort(404);
            }
            $data = $template->toArray();
        } else {
        }
        return $this->makeView('admin.custom_templates.edit', [
            'data' => $data
        ]);
    }

    public function postTemplate()
    {

        $form = [
            'id' => intval(Input::get('id')),
            'name' => trim(Input::get('name')),
            'colors' => $this->normalizeColors(Input::get('colors'))
        ];

        $validator = Validator::make(
            $form,
            array(
                'name' => array('required'),
                'colors' => array('required'),
            )
        );

        if ($validator->fails()) {
            return Redirect::action('Admin\CustomTemplatesController@getTemplate', [
                'id' => $form['id']
            ])->withErrors($validator)->with('data', $form);
        }

        if ($form['id'] > 0) {
            $template = \CustomTemplate::find($form['id']);
            if (empty($template)) {
                App::abort(404);
            }
        } else {
            $template = new \CustomTemplate();
        }

        $background = Input::file('background');
        if ($form['id'] === 0 && empty($background)) {
            throw new UploadException('Не удалось загрузить файл');
        }

        try {
            DB::beginTransaction();
            $template->name = $form['name'];
            $template->colors = $form['colors'];

            if (!empty($background)) {
                $template->extension = $background->guessExtension();
                $path = Config::get('mamaprint.custom_templates_dir') . DIRECTORY_SEPARATOR . $template->id;
                if (!@file_exists($path)) {
                    if (@mkdir($path, 0777, true) !== true) {
                        throw new Exception(Lang::get('messages.error.could_not_create_folder', [
                            'path' => $path
                        ]));
                    }
                }
                $background->move($path, 'original.' . $template->extension);
            }

            $template->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
        }

        return $this->withSuccessMessage(Redirect::action('Admin\CustomTemplatesController@getTemplates'), 'Шаблон сохранен');
    }

    private function normalizeColors($colors)
    {
        return implode(",", array_filter(array_map(function ($item) {
            $item = trim($item);
            return preg_match('/#[a-fA-F0-9]{3,6}/', $item) ? $item : null;
        }, explode(",", $colors))));
    }

}
