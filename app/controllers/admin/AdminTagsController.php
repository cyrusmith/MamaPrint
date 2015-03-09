<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 05.03.2015
 * Time: 16:47
 */

namespace Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;

class AdminTagsController extends AdminController
{

    public function getTags()
    {
        $query = \Tag::orderBy('weight', 'asc');

        $search = Input::get('search');
        $type = Input::get('type');
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('tag', 'LIKE', "%$search%");
            });
        }

        if (!empty($type)) {
            $query->where('type', '=', $type);
        }

        $tags = $query->paginate(20);

        $this->setPageTitle('Тэги');
        return $this->makeView("admin.tags.index", [
            'tags' => $tags,
            'search' => $search,
            'type' => $type,
        ]);

    }

    public function deleteTag($id)
    {
        DB::transaction(function () use ($id) {
            $tag = \Tag::find($id);
            if ($tag) {
                $tag->delete($id);
            }
            DB::table('taggables')->where('tag_id', '=', $id)->delete();
        });

        return Redirect::back();
    }

    public function postReorder()
    {
        $weights = Input::get('weights');
        try {
            DB::beginTransaction();

            foreach ($weights as $id => $value) {
                $item = \Tag::find($id);
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

}