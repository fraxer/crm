<?php

namespace backend\modules\stories\helpers;

use Yii;
use backend\modules\stories\models\StoriesPhoto;
use yii\web\UploadedFile;
use yii\base\UserException;

class PoolFiles
{
    // private $albumId;

    private $pool = [];

    // function __construct($albumId)
    // {
    //     $this->albumId = $albumId;
    // }

    public function add($path)
    {
        $this->pool[] = $path;
    }

    public function deleteFiles()
    {
        foreach ($this->pool as $path) {
            $fullpath = \Yii::$app->basePath . $path; 

            unlink($fullpath);

            $this->removeEmptyDirectories(dirname(dirname($fullpath)));
        }
    }

    private function removeEmptyDirectories($path)
    {
        $empty = true;

        foreach (glob($path . DIRECTORY_SEPARATOR . "*") as $file) {
            $empty &= is_dir($file) && $this->removeEmptyDirectories($file);
        }

        return $empty && (is_readable($path) && count(scandir($path)) == 2) && rmdir($path);
    }
}
