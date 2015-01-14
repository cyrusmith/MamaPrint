<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 14.01.2015
 * Time: 13:18
 */

namespace Admin;


use Illuminate\Support\Facades\App;

class AdminAttachmentController
{

    public function update($id)
    {

    }

    public function delete($id)
    {

        try {
            DB::beginTransaction();

            App::make('AttachmentService')->deleteAttachment($id);

            DB::commit();
        } catch (Exception $e) {
            DB:
            rollback();
        }

    }


}