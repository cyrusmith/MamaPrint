<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 15.01.2015
 * Time: 17:47
 */

use \Symfony\Component\HttpFoundation\File\UploadedFile;
use Gallery\GalleryImage;
use \Illuminate\Support\Facades\Lang;
use \Illuminate\Support\Facades\App;

class GalleryService
{

    const DS = DIRECTORY_SEPARATOR;

    private static $ALLOWED_TYPES = [
        'image/gif',
        'image/jpeg',
        'image/pjpeg',
        'image/png',
        'image/bmp',
        'image/vnd.wap.wbmp',
    ];

    /**
     * @return array of image info
     */
    public function saveImage($gallery, UploadedFile $file)
    {

        if (!in_array($file->getMimeType(), self::$ALLOWED_TYPES)) {
            throw new Exception(Lang::get('messages.gallery.filenotallowed', [
                'type' => $file->getMimeType()
            ]));
        }

        try {
            DB::beginTransaction();

            $DS = self::DS;
            $path = Config::get('mamaprint.galleries_path') . $DS . $gallery->gallery_relation_id . $DS . $gallery->id;
            if (!file_exists($path)) {
                if (mkdir($path, 0777, true) !== true) {
                    throw new Exception(Lang::get('messages.error.could_not_create_folder', [
                        'path' => $path
                    ]));
                }
            }

            list($width, $height, $type, $attr) = getimagesize($file->getRealPath());

            $image = new GalleryImage;

            if ($width == 0 || $height == 0) {
                throw new Exception(Lang::get('messages.gallery.notanimage', [
                    'file' => $file->getClientOriginalName()
                ]));
            }

            $image->width = $width;
            $image->height = $height;
            $image->mime = $file->getMimeType();
            $image->extension = $file->guessExtension();

            $image->save();
            $gallery->images()->save($image);

            $file->move($path, $image->id . '.' . $image->extension);

            DB::commit();

            return $image;

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

    }

    public function deleteImage($id)
    {
        try {
            DB::beginTransaction();

            $galleryImage = GalleryImage::find($id);

            if (empty($galleryImage)) {
                throw new Exception("messages.error.attachment_not_found");
            }

            $galleryImage->delete();

            $DS = DIRECTORY_SEPARATOR;
            $path = Config::get('mamaprint.galleries_path') . $DS . $galleryImage->gallery_id . $DS . $galleryImage->id . '.' . $galleryImage->extension;
            $cachePath = Config::get('mamaprint.galleries_path') . $DS . 'cache';

            if (file_exists($path)) {
                unlink($path);
            }

            if ($handle = opendir($cachePath)) {
                while (false !== ($entry = readdir($handle))) {
                    if ($entry == "." || $entry == "..") continue;
                    if (starts_with($entry, $galleryImage->gallery_id . '-' . $galleryImage->id . '-')) {
                        unlink($cachePath . $DS . $entry);
                    }
                }
                closedir($handle);
            }


            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }
    }

    public function outputImage($id, $width = 0, $height = 0, $crop = false)
    {

        $image = GalleryImage::find($id);

        if (empty($image)) {
            throw new Exception(Lang::get('messages.filenotfound'), 404);
        }

        $gallery = \Gallery\Gallery::find($image->gallery_id);

        if (empty($gallery)) {
            throw new Exception(Lang::get('messages.filenotfound'), 404);
        }

        $typeToImageFunctions = [
            'image/gif' => ['imagecreatefromgif', 'imagegif'],
            'image/jpeg' => ['imagecreatefromjpeg', 'imagejpeg'],
            'image/pjpeg' => ['imagecreatefromjpeg', 'imagejpeg'],
            'image/png' => ['imagecreatefrompng', 'imagepng'],
            'image/bmp' => ['imagecreatefrombmp', 'imagewbmp'],
            'image/vnd.wap.wbmp' => ['imagecreatefromebmp', 'imagewbmp']
        ];

        $DS = self::DS;
        $path = Config::get('mamaprint.galleries_path') . $DS . $gallery->id . $DS . $image->id . '.' . $image->extension;

        if (!file_exists($path)) {
            throw new Exception(Lang::get('messages.filenotfound'), 404);
        }

        $im = null;
        try {

            if ($width > 0 && $height > 0) {

                $cacheFolder = Config::get('mamaprint.galleries_path') . $DS . 'cache';

                if (!file_exists($cacheFolder)) {
                    if (mkdir($cacheFolder, 0777, true) !== true) {
                        throw new Exception(Lang::get('messages.error.could_not_create_folder', [
                            'path' => $cacheFolder
                        ]));
                    }
                }

                $cachePath = $cacheFolder . $DS . $gallery->id . '-' . $image->id . '-' . md5($width . '-' . $height . '-' . strval($crop));

                if (!file_exists($cachePath)) {
                    $this->imageResize($path, $cachePath, $width, $height, $crop);
                }

                $path = $cachePath;

            }

            $im = $typeToImageFunctions[$image->mime][0]($path);

            header('Content-Type: ' . $image->mime);
            header('Cache-Control: max-age=86400');
            header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 86400));
            header('Content-Type: image/png');
            $typeToImageFunctions[$image->mime][1]($im);
        } catch (Exception $e) {
            if ($im) {
                imagedestroy($im);
                $im = null;
            }
            throw new $e;
        }

        if ($im) {
            imagedestroy($im);
            $im = null;
        }

    }

    private function imageResize($src, $dst, $width, $height, $crop = false)
    {

        if (!list($w, $h) = getimagesize($src)) new Exception("Unsupported picture type");

        $type = strtolower(substr(strrchr($src, "."), 1));
        if ($type == 'jpeg') $type = 'jpg';
        switch ($type) {
            case 'bmp':
                $img = imagecreatefromwbmp($src);
                break;
            case 'gif':
                $img = imagecreatefromgif($src);
                break;
            case 'jpg':
                $img = imagecreatefromjpeg($src);
                break;
            case 'png':
                $img = imagecreatefrompng($src);
                break;
            default :
                throw new Exception("Unsupported picture type " . $type);
        }

        if ($crop) {
            if ($w < $width or $h < $height) throw new Exception("Picture is too small!");
            $ratio = max($width / $w, $height / $h);
            $h = $height / $ratio;
            $x = ($w - $width / $ratio) / 2;
            $w = $width / $ratio;
        } else {
            if ($w < $width and $h < $height) throw new Exception("Picture is too small!");
            $ratio = min($width / $w, $height / $h);
            $width = $w * $ratio;
            $height = $h * $ratio;
            $x = 0;
        }

        $new = imagecreatetruecolor($width, $height);

        if ($type == "gif" or $type == "png") {
            imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
            imagealphablending($new, false);
            imagesavealpha($new, true);
        }

        imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);

        switch ($type) {
            case 'bmp':
                imagewbmp($new, $dst);
                break;
            case 'gif':
                imagegif($new, $dst);
                break;
            case 'jpg':
                imagejpeg($new, $dst, 95);
                break;
            case 'png':
                imagepng($new, $dst, 0);
                break;
        }

        imagedestroy($new);
        imagedestroy($img);

    }

}