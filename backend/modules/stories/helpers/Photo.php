<?php

namespace backend\modules\stories\helpers;

use Yii;
use backend\modules\stories\models\StoriesPhoto;
use backend\modules\stories\helpers\FileStructure;
use backend\modules\stories\helpers\ImageThumbnail;
use yii\web\UploadedFile;
use yii\base\UserException;

class Photo
{
    static public function create(&$file, $albumId)
    {
        $fileStructure = new FileStructure($albumId);

        $fileStructure->createDirectoryAlbumPhotos();

        $photoPath = $fileStructure->createAlbumPhotoPath($file->extension);

        $model = new StoriesPhoto();
        $model->scenario = StoriesPhoto::SCENARIO_CREATE;
        $model->album_id = $albumId;
        $model->imageFile = $file;
        $model->image = $photoPath;
        $model->rank = self::getCountPhotos($albumId) + 1;
        $model->duration = 5;

        if (!$model->save()) {
            throw new UserException('Error create photo');
        }

        if (!$model->upload($photoPath)) {
            throw new UserException('Error opload photo image');
        }

        $thumbnailPath = ImageThumbnail::createThumbnail($photoPath);

        ImageThumbnail::scaleImage($photoPath);

        $model->updateAttributes(['thumb' => $thumbnailPath]);

        return [$photoPath, $thumbnailPath];
    }

    static public function delete(&$model)
    {
        $modelImage = $model->image;

        @unlink($model->image);
        @unlink($model->thumb);

        FileStructure::removeEmptyDirectories(dirname(dirname($modelImage)));

        $model->delete();
    }

    static public function updateRankAdjacentPhotos(&$model)
    {
        $photos = StoriesPhoto::find()
            ->where(['album_id' => $model->album_id])
            ->andWhere(['<>', 'id', $model->id])
            ->orderBy('rank asc')
            ->all()
        ;

        if ($model->rank > count($photos) + 1) {
            $model->rank = count($photos) + 1;
        }

        $rank = 1;

        foreach ($photos as $photo) {
            if ($model->rank == $rank) {
                $rank++;
            }

            if ($photo->rank != $rank) {
                $photo->updateAttributes(['rank' => $rank]);
            }
            
            $rank++;
        }
    }

    static public function updateRankPhotos($albumId)
    {
        $photos = StoriesPhoto::find()
            ->where(['album_id' => $albumId])
            ->orderBy('rank asc')
            ->all()
        ;

        $rank = 1;

        foreach ($photos as $photo) {
            if ($photo->rank != $rank) {
                $photo->updateAttributes(['rank' => $rank]);
            }

            $rank++;
        }
    }

    static private function getCountPhotos($albumId)
    {
        return StoriesPhoto::find()->where(['album_id' => $albumId])->count();
    }
}
