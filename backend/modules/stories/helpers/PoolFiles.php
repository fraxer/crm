<?php

namespace backend\modules\stories\helpers;

use Yii;
use backend\modules\stories\models\StoriesPhoto;
use backend\modules\stories\helpers\FileStructure;
use yii\web\UploadedFile;
use yii\base\UserException;

class PoolFiles
{
    private $pool = [];

    public function add($path)
    {
        $this->pool[] = $path;
    }

    public function deleteFiles()
    {
        foreach ($this->pool as $path) {
            $fullpath = $path; 

            @unlink($fullpath);

            FileStructure::removeEmptyDirectories(dirname(dirname($fullpath)));
        }
    }
}
