<?php

namespace backend\modules\stories\controllers;

use backend\modules\stories\models\StoriesPhoto;
use backend\modules\stories\helpers\Photo;
use backend\modules\stories\helpers\FileStructure;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\base\UserException;
use yii\filters\AccessControl;

class PhotoController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = StoriesPhoto::SCENARIO_UPDATE;

        if ($this->request->isPost) {

            if (!$model->load($this->request->post())) {
                throw new UserException('can\'t load model');
            }

            if ($model->getOldAttribute('rank') != $model->rank) {
                Photo::updateRankAdjacentPhotos($model);
            }

            if (!$model->save()) {
                throw new UserException('cant save album');
            }

            return $this->redirect(['default/update', 'id' => $model->album_id]);
        }

        return $this->redirect(['default/update', 'id' => $model->album_id]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $albumId = $model->album_id;

        Photo::delete($model);

        Photo::updateRankPhotos($albumId);

        return $this->redirect(['default/update', 'id' => $albumId]);
    }

    protected function findModel($id)
    {
        if (($model = StoriesPhoto::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
