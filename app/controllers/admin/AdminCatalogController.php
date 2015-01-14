<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 05.01.2015
 * Time: 12:24
 */

namespace Admin;


use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Catalog\CatalogItem;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class AdminCatalogController extends AdminController
{

    public function index()
    {

        $items = CatalogItem::paginate(10);
        $this->setPageTitle(Lang::get('static.admin.pagetitle.catalog'));
        $this->addToolbarAction('add', 'Новый', 'catalog/add');
        return $this->makeView("admin.catalog.index", [
            'items' => $items
        ]);
    }

    public function add()
    {
        $item = new CatalogItem;
        $this->setPageTitle(Lang::get('static.admin.pagetitle.addcatalogitem'));
        $this->addToolbarAction('save', 'Сохранить', 'catalog/save', 'post');
        $this->addToolbarAction('cancel', 'Отмена', 'catalog');
        return $this->makeView("admin.catalog.save", [
                'data' => $item->toArray()
            ]
        );
    }

    public function edit($id)
    {

        $item = CatalogItem::find($id);

        if (empty($item)) {
            App::abort(404);
        }

        $attachments = \Attachment::ofModel(\Attachment::MODEL_CATALOGITEM)->where('model_id', '=', $id)->get();

        $this->setPageTitle($item->title);
        $this->addToolbarAction('save', 'Сохранить', 'catalog/save', 'post');
        $this->addToolbarAction('cancel', 'Отмена', 'catalog');
        return $this->makeView("admin.catalog.save", [
            'data' => $item->toArray(),
            'attachments' => $attachments->toJSON()
        ]);
    }

    public function save()
    {

        $id = intval(Input::get('id'));

        $isNew = $id > 0;

        $form = array(
            'id' => Input::get('id'),
            'title' => Input::get('title'),
            'slug' => Input::get('slug'),
            'short_description' => mb_strtolower(Input::get('short_description')),
            'long_description' => Input::get('long_description'),
            'price' => intval(floatval(Input::get('price')) * 100),
            'registered_price' => intval(floatval(Input::get('registered_price')) * 100),
            'old_price' => intval(floatval(Input::get('old_price')) * 100),
            'info_age' => Input::get('info_age'),
            'info_targets' => Input::get('info_targets'),
            'info_level' => Input::get('info_level'),
        );

        $validator = Validator::make(
            $form,
            array(
                'title' => array('required'),
                'slug' => array('required'),
                'short_description' => array('required'),
                'price' => array('required', 'numeric', 'min:1'),
                'registered_price' => array('numeric'),
                'old_price' => array('numeric'),
            )
        );

        if ($validator->fails()) {
            return Redirect::action('Admin\AdminCatalogController@' . ($id ? 'edit' : 'add'), $id ? [
                'id' => $id
            ] : [])->withErrors($validator)->with('data', $form);
        }

        try {
            DB::beginTransaction();
            $item = null;
            if ($form['id']) {
                $item = CatalogItem::find($id);
                if (!$item) {
                    throw new Exception('Item ' . $id . ' not found');
                }
            } else {
                $item = new CatalogItem();
            }

            $item->title = $form['title'];
            $item->slug = $form['slug'];
            $item->short_description = $form['short_description'];
            $item->long_description = $form['long_description'];
            $item->price = $form['price'];
            $item->registered_price = $form['registered_price'];
            $item->old_price = $form['old_price'];
            $item->info_age = $form['info_age'];
            $item->info_targets = $form['info_targets'];
            $item->info_level = $form['info_level'];

            $item->save();

            $id = $item->id;

            $files = Input::file();

            if (array_key_exists('attachment', $files)) {

                for ($i = 0; $i < count($files['attachment']); $i++) {
                    $file = $files['attachment'][$i];
                    $title = Input::get('attachment_title.' . $i);
                    $description = Input::get('attachment_description.' . $i);

                    $attachment = new \Attachment();
                    $attachment->title = $title;
                    $attachment->description = $description;
                    $attachment->mime = is_string($file->getMimeType()) ? $file->getMimeType() : $file->getClientMimeType();
                    $attachment->extension = is_string($file->guessExtension()) ? $file->guessExtension() : $file->getExtension();
                    $attachment->size = $file->getClientSize();
                    $attachment->model = \Attachment::MODEL_CATALOGITEM;
                    $attachment->model_id = $id;

                    $attachment->save();

                    App::make('AttachmentService')->saveUploadedFile($file, $attachment);

                }

            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return $this->withErrorMessage(Redirect::action('Admin\AdminCatalogController@' . ($form['id'] ? 'edit' . $form['id'] : 'add')), $e->getMessage());
        }

        return $this->withSuccessMessage(Redirect::action('Admin\AdminCatalogController@edit', [
            'id' => $id
        ]), Lang::get('messages.admin.catalogitemsaved'));
    }

}