<?php

namespace backend\modules\stories\helpers;

use Yii;
use backend\modules\stories\models\StoriesPhoto;
use yii\web\UploadedFile;
use yii\base\UserException;

class Album
{
    private $albumId;

    function __construct($albumId)
    {
        $this->albumId = $albumId;
    }

    public function create(&$file)
    {
        $modelPhoto = new StoriesPhoto();
        $modelPhoto->album_id = $this->albumId;
        $modelPhoto->imageFile = $file;
        $modelPhoto->image = $modelPhoto->getFilePath();
        $modelPhoto->rank = $this->getCountPhotos() + 1;
        $modelPhoto->duration = 10;

        if (!$modelPhoto->save()) {
            throw new UserException('Error create photo');
        }

        if (!$modelPhoto->upload()) {
            throw new UserException('Error opload photo image');
        }

        return $modelPhoto->getFilePath();
    }

    private function getCountPhotos()
    {
        return StoriesPhoto::find()->where(['album_id' => $this->albumId])->count();
    }
}
