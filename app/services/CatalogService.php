<?php

/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 01.02.2015
 * Time: 15:40
 */
class CatalogService
{

    public function getItemAttachmentPath($itemId)
    {
        $catalogItem = \Catalog\CatalogItem::find($itemId);

        if (empty($catalogItem)) {
            throw new Exception("Материал не найден");
        }

        $attachmentsService = App::make('AttachmentService');

        $attachments = $catalogItem->attachments;
        if ($attachments->count() == 1) {
            return $attachmentsService->getFilePath($attachments->first()->id);
        }

        $DS = DIRECTORY_SEPARATOR;
        $path = Config::get('mamaprint.tmp_catalog_items');
        $file = $path . $DS . $catalogItem->id . ".zip";

        if (!file_exists($file)) {

            if (!file_exists($path)) {
                if (mkdir($path, 0777, true) !== true) {
                    throw new Exception(Lang::get('messages.error.could_not_create_folder', [
                        'path' => $path
                    ]));
                }
            }

            $zip = new ZipArchive();
            $zip->open($file, ZipArchive::CREATE);

            $n = 1;
            foreach ($attachments as $attachment) {
                $attachmentPath = $attachmentsService->getFilePath($attachment->id);
                if ($attachmentPath) {
                    $zip->addFile($attachmentPath, $n . '.' . $attachment->extension);
                }
                $n++;
            }

            $zip->close();
        }

        return $file;

    }

    public function cleanDownloadCache($itemId)
    {
        $catalogItem = \Catalog\CatalogItem::find($itemId);

        if (empty($catalogItem)) {
            throw new Exception("Материал не найден");
        }

        $path = Config::get('mamaprint.tmp_catalog_items');
        $file = $path . DIRECTORY_SEPARATOR . $catalogItem->id . ".zip";
        if (file_exists($file)) {
            @unlink($file);
        }

    }

}