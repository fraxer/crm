<?php

namespace backend\modules\stories\helpers;

use Yii;

class FileStructure
{
    private $albumId;
   
    function __construct($albumId)
    {
        $this->albumId = $albumId;
    }

    public function createDirectoryAlbumImage()
    {
        return $this->createDirectory('getPathAlbumImage');
    }

    public function createDirectoryAlbumPhotos()
    {
        return $this->createDirectory('getPathAlbumPhotos');
    }

    public function createAlbumImagePath($extension)
    {
        $path = $this->getPathAlbumImage();
        $path .= '/' . $this->getRandomFileName();
        $path .= '.' . $extension;

        return $path;
    }

    public function createAlbumPhotoPath($extension)
    {
        $path = $this->getPathAlbumPhotos();
        $path .= '/' . $this->getRandomFileName();
        $path .= '.' . $extension;

        return $path;
    }

    private function createDirectory($fn)
    {
        $dir = call_user_func(array($this, $fn));

        if (!is_dir($dir)) mkdir($dir, 0755, true);

        return $dir;
    }

    private function getPathAlbum()
    {
        $path = Yii::$app->getModule('stories')->params['uploadDirectory'];
        $path .= '/' . $this->albumId;

        return $path;
    }

    private function getPathAlbumImage()
    {
        $path = $this->getPathAlbum();
        $path .= '/image';

        return $path;
    }

    private function getPathAlbumPhotos()
    {
        $path = $this->getPathAlbum();
        $path .= '/photos';

        return $path;
    }

    private function getRandomFileName()
    {
        return Yii::$app->security->generateRandomString(30);
    }

    static public function removeEmptyDirectories($path)
    {
        $empty = true;

        foreach (glob($path . DIRECTORY_SEPARATOR . "*") as $file) {
            $empty &= is_dir($file) && self::removeEmptyDirectories($file);
        }

        return $empty && (is_readable($path) && count(scandir($path)) == 2) && rmdir($path);
    }
}
