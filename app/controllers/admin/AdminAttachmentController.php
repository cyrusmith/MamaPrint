<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 14.01.2015
 * Time: 13:18
 */

namespace Admin;


use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;

class AdminAttachmentController extends AdminController
{

    public function view()
    {

    }

    public function download($id)
    {
        $path = App::make('AttachmentService')->getFilePath($id);
        $file = \Attachment::find($id);
        if (empty($file) || empty($path)) {
            App::abort(404);
        }
        return Response::download($path, "attachment" . $file->id . '.' . $file->extension);
    }

    public function add()
    {

    }

    public function update($id)
    {
        $data = Input::all();

        try {
            DB::beginTransaction();

            $attachment = \Attachment::find($id);

            if (!empty($data['title'])) {
                $attachment->title = $data['title'];
            }
            if (!empty($data['description'])) {
                $attachment->description = $data['description'];
            }
            $attachment->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(array(
                "status" => false,
                "error" => $e->getMessage(),
                "data" => $data,
                "_links" => [
                    'self' => URL::action('Admin\AdminAttachmentController@view', [
                        'id' => $id
                    ])
                ]
            ), 500);
        }

        return Response::json(array(
            "status" => true,
            "data" => $data,
            "_links" => [
                'self' => URL::action('Admin\AdminAttachmentController@view', [
                    'id' => $id
                ])
            ]
        ), 200);
    }

    public function delete($id)
    {

        try {
            App::make('AttachmentService')->deleteAttachment($id);
            return Response::json(array(
                "status" => true
            ), 200);
        } catch (Exception $e) {
            return Response::json([
                "status" => false
            ], 500);
        }
    }


}