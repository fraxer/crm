<?php

namespace backend\modules\stories\helpers;

use Yii;
use backend\modules\stories\models\StoriesPhoto;
use backend\modules\stories\helpers\FileStructure;
use yii\web\UploadedFile;
use yii\base\UserException;

class Photo
{
    private $albumId;

    function __construct($albumId)
    {
        $this->albumId = $albumId;
    }

    public function create(&$file)
    {
        $fileStructure = new FileStructure($this->albumId);

        $fileStructure->createDirectoryAlbumPhotos();

        $albumPhotoPath = $fileStructure->createAlbumPhotoPath($file->extension);

        $model = new StoriesPhoto();
        $model->album_id = $this->albumId;
        $model->imageFile = $file;
        $model->image = $albumPhotoPath;
        $model->rank = $this->getCountPhotos() + 1;
        $model->duration = 10;

        if (!$model->save()) {
            throw new UserException('Error create photo');
        }

        if (!$model->upload($albumPhotoPath)) {
            throw new UserException('Error opload photo image');
        }

        return $albumPhotoPath;
    }

    private function getCountPhotos()
    {
        return StoriesPhoto::find()->where(['album_id' => $this->albumId])->count();
    }
}
