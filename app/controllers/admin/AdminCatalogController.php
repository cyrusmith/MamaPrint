<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 05.01.2015
 * Time: 12:24
 */

namespace Admin;


use Gallery\Gallery;
use Gallery\GalleryImage;
use Gallery\GalleryRelation;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Catalog\CatalogItem;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use \Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AdminCatalogController extends AdminController
{

    public function index()
    {

        $search = Input::get('search');
        $query = CatalogItem::orderBy('weight', 'desc');
        if (!empty($search)) {
            if (!empty($search)) {
                $query->where('title', 'LIKE', '%' . $search . '%');
            }
        }

        $items = $query->paginate(100);

        $this->setPageTitle(Lang::get('static.admin.pagetitle.catalog'));
        $this->addToolbarAction('add', 'Новый', 'catalog/add');
        if (Request::ajax()) {
            return Request::json(200, $items->all());
        }
        return $this->makeView("admin.catalog.index", [
            'items' => $items,
            'search' => $search
        ]);
    }

    public function add()
    {
        $item = new CatalogItem;
        $this->setPageTitle(Lang::get('static.admin.pagetitle.addcatalogitem'));
        $this->addToolbarAction('save', 'Сохранить', 'catalog/save', 'post');
        $this->addToolbarAction('cancel', 'Отмена', 'catalog');
        return $this->makeView("admin.catalog.save", [
                'data' => array_merge($item->toArray(), [
                    'tags' => $item->getTagsAsString(),
                    'related' => $item->relatedItems,
                    'relatedids' => $this->getRelatedIdsString($item),
                    'relatedtitles' => $this->getRelatedTitlesString($item),
                    'weight' => DB::table('catalog_items')->max('weight') + 1
                ])
            ]
        );
    }

    public function getItem($id)
    {

        $item = CatalogItem::find($id);

        if (empty($item)) {
            App::abort(404);
        }

        $attachments = $item->attachments;

        $gallery = $item->galleries()->first();

        $this->setPageTitle($item->title);
        $this->addToolbarAction('save', 'Сохранить', 'catalog/save', 'post');
        $this->addToolbarAction('cancel', 'Отмена', 'catalog');
        return $this->makeView("admin.catalog.save", [
            'data' => array_merge($item->toArray(), [
                'tags' => $item->getTagsAsString(),
                'related' => $item->relatedItems,
                'relatedids' => $this->getRelatedIdsString($item),
                'relatedtitles' => $this->getRelatedTitlesString($item)

            ]),
            'attachments' => $attachments ? $attachments->toJSON() : json_encode([]),
            'images' => $gallery ? $gallery->images->toJSON() : json_encode([])
        ]);
    }

    private function getRelatedIdsString($item)
    {
        $ids = [];
        foreach ($item->relatedItems as $relItem) {
            $ids[] = $relItem->id;
        }
        return implode(",", $ids);
    }

    private function getRelatedTitlesString($item)
    {
        $titles = [];
        foreach ($item->relatedItems as $relItem) {
            $titles[] = $relItem->title;
        }
        return implode(",", $titles);
    }

    public function postReorder()
    {
        $weights = Input::get('weights');
        try {
            DB::beginTransaction();

            foreach ($weights as $id => $value) {
                $item = CatalogItem::find($id);
                if ($item) {
                    $item->weight = $value;
                    $item->save();
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rolback();
        }
        return Redirect::back();
    }

    public function postItem()
    {

        $id = intval(Input::get('id'));

        $isNew = $id === 0;

        $form = array(
            'id' => Input::get('id'),
            'title' => str_replace('"', '&quot;', Input::get('title')),
            'active' => Input::get('active'),
            'weight' => Input::get('weight'),
            'slug' => mb_strtolower(Input::get('slug')),
            'short_description' => str_replace('"', '&quot;', Input::get('short_description')),
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

        $tags = array_filter(array_map(function ($tag) {
            return trim($tag);
        }, explode(",", Input::get('tags'))), function ($tag) {
            return !empty($tag);
        });

        if (empty($tags)) {
            $tags = [];
        }

        $messages = [];

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

            $item->active = $form['active'];
            $item->weight = $form['weight'];
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

                    $item->attachments()->save($attachment);

                    App::make('AttachmentService')->saveUploadedFile($file, $attachment);

                }

                App::make('CatalogService')->cleanDownloadCache($item->id);

            }

            $item->updateTags($tags);

            if ($isNew) {
                $gallery = new Gallery();
                $item->galleries()->save($gallery);
            }

            if (array_key_exists('gallery_image', $files)) {

                $gallery = $item->galleries()->first();

                for ($i = 0; $i < count($files['gallery_image']); $i++) {
                    try {
                        App::make('GalleryService')->saveImage($gallery, $files['gallery_image'][$i]);
                    } catch (Exception $e) {
                        $messages[] = $e->getMessage();
                    }
                }

            }

            $relatedIds = array_filter(explode(",", Input::get('related')));

            $item->relatedItems()->detach();
            if (is_array($relatedIds) && count($relatedIds) > 0) {
                $item->relatedItems()->attach($relatedIds);
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            return $this->withErrorMessage(Redirect::action('Admin\AdminCatalogController@' . ($form['id'] ? 'edit' : 'add'), [
                'id' => $id
            ]), $e->getMessage());
        }

        return $this->withSuccessMessage(Redirect::action('Admin\AdminCatalogController@getItem', [
            'id' => $id
        ]), empty($messages) ? Lang::get('messages.admin.catalogitemsaved') : implode('<br>', $messages));
    }

}