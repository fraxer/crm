<?php

namespace backend\modules\stories\helpers;

use Yii;
use backend\modules\stories\models\StoriesAlbum;
use backend\modules\stories\helpers\FileStructure;
use backend\modules\stories\helpers\ImageThumbnail;
use backend\modules\stories\helpers\Photo;
use yii\web\UploadedFile;
use yii\base\UserException;

class Album
{
    static public function loadAndSave(&$model)
    {
        self::load($model);
        self::save($model);
    }

    static public function load(&$model)
    {
        $request = \Yii::$app->request;

        if (!$model->load($request->post())) {
            throw new Exception('can\'t load model');
        }

        $model->albumImage = UploadedFile::getInstance($model, 'albumImage');
        $model->photoImages = UploadedFile::getInstances($model, 'photoImages');
    }

    static public function save(&$model)
    {
        if (!$model->save()) {
            throw new UserException('cant save album');
        }
    }

    static public function setRankOnCreate(&$model)
    {
        $albumCount = self::getCountAlbums();

        if ($model->rank != $albumCount) {
            $model->updateAttributes(['rank' => $albumCount]);
        }
    }

    static public function setRankOnUpdate(&$model)
    {
        \Yii::debug('rank '. $model->getOldAttribute('rank'));
        \Yii::debug('rank '. $model->rank);

        if ($model->getOldAttribute('rank') != $model->rank) {
            self::updateRankAdjacentAlbums($model);
        }
    }

    static public function prepareFileStructure(&$model)
    {
        $fileStructure = new FileStructure($model->id);

        $fileStructure->createDirectoryAlbumImage();

        return $fileStructure->createAlbumImagePath($model->albumImage->extension);
    }

    static public function uploadAndCropImage(&$model, $albumImagePath)
    {
        if (!$model->upload($albumImagePath)) {
            throw new UserException('Error opload album image');
        }

        $model->updateAttributes(['image' => $albumImagePath]);

        ImageThumbnail::cropImage($albumImagePath);
    }

    static public function deleteImageFile($path)
    {
        if (!empty($path)) @unlink($path);
    }

    static public function delete(&$model)
    {
        $modelImage = $model->image;

        self::deleteImageFile($model->image);

        foreach ($model->photos as $photoModel) {
            Photo::delete($photoModel);
        }

        FileStructure::removeEmptyDirectories(dirname(dirname($modelImage)));

        $model->delete();
    }

    static public function updateRankAdjacentAlbums(&$model)
    {
        $albums = StoriesAlbum::find()
            ->andWhere(['<>', 'id', $model->id])
            ->orderBy('rank asc')
            ->all()
        ;

        if ($model->rank > count($albums) + 1) {
            $model->rank = count($albums) + 1;
        }

        $rank = 1;

        foreach ($albums as $album) {
            if ($model->rank == $rank) {
                $rank++;
            }

            if ($album->rank != $rank) {
                $album->updateAttributes(['rank' => $rank]);
            }

            $rank++;
        }
    }

    static public function updateRankAlbums($albumId)
    {
        $albums = StoriesAlbum::find()
            ->orderBy('rank asc')
            ->all()
        ;

        $rank = 1;

        foreach ($albums as $album) {
            if ($album->rank != $rank) {
                $album->updateAttributes(['rank' => $rank]);
            }

            $rank++;
        }
    }

    static public function getCountAlbums()
    {
        return StoriesAlbum::find()->count();
    }
}
