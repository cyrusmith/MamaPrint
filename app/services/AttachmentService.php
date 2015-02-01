<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 14.01.2015
 * Time: 12:20
 */

use Symfony\Component\HttpFoundation\File\UploadedFile;

use \Illuminate\Support\Facades\Lang;

class AttachmentService
{
    public function saveUploadedFile(UploadedFile $file, Attachment $attachment)
    {

        $path = Config::get('mamaprint.attachments_path') . DIRECTORY_SEPARATOR . $attachment->id;
        if (!file_exists($path)) {
            if (mkdir($path, 0777, true) !== true) {
                throw new Exception(Lang::get('messages.error.could_not_create_folder', [
                    'path' => $path
                ]));
            }
        }

        return $file->move($path, 'original.' . $attachment->extension);

    }

    public function getFilePath($id)
    {
        $attachment = Attachment::find($id);
        $DS = DIRECTORY_SEPARATOR;
        $path = Config::get('mamaprint.attachments_path') . $DS . $attachment->id . $DS . 'original.' . $attachment->extension;
        return file_exists($path) ? $path : null;
    }

    public function deleteAttachment($id)
    {
        try {
            DB::beginTransaction();

            $attachment = Attachment::find($id);

            if (empty($attachment)) {
                throw new Exception("messages.error.attachment_not_found");
            }

            $attachment->delete();

            $DS = DIRECTORY_SEPARATOR;
            $path = Config::get('mamaprint.attachments_path') . $DS . $attachment->id;

            if (file_exists($path)) {
                if ($handle = opendir($path)) {
                    while (false !== ($entry = readdir($handle))) {
                        if ($entry == "." || $entry == "..") continue;

                        if (unlink($path . $DS . $entry) !== true) {
                            throw new Exception(Lang::get("messages.error.could_not_delete_file", [
                                'file' => $path . $DS . $entry
                            ]));
                        }
                    }

                    if (rmdir($path) !== true) {
                        throw new Exception(Lang::get("messages.error.could_not_delete_folder", [
                            'folder' => $path
                        ]));
                    }

                    closedir($handle);
                }

            }


            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }

    }

}