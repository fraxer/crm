<?php

namespace backend\modules\stories\controllers;

use Yii;
use backend\modules\stories\models\StoriesAlbum;
use backend\modules\stories\models\StoriesPhoto;
use backend\modules\stories\helpers\Photo;
use backend\modules\stories\helpers\PoolFiles;
use backend\modules\stories\helpers\FileStructure;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\base\UserException;
use yii\filters\VerbFilter;
use yii\db\Query;

class DefaultController extends Controller
{
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionIndex()
    {
        return $this->render('index', [
            'albums' => StoriesAlbum::find()->orderBy('id desc')->all(),
        ]);
    }

    public function actionCreate()
    {
        $model = new StoriesAlbum();

        if ($this->request->isPost) {
            $poolFiles = new PoolFiles();

            $transaction = \Yii::$app->db->beginTransaction();

            try {
                if (!$model->load($this->request->post())) {
                    throw new Exception('can\'t load model');
                }

                $model->albumImage = UploadedFile::getInstance($model, 'albumImage');
                $model->photoImages = UploadedFile::getInstances($model, 'photoImages');

                if (!$model->save()) {
                    throw new UserException('cant save album');
                }

                $fileStructure = new FileStructure($model->id);

                $fileStructure->createDirectoryAlbumImage();

                $albumImagePath = $fileStructure->createAlbumImagePath($model->albumImage->extension);

                if (!$model->upload($albumImagePath)) {
                    throw new UserException('Error opload album image');
                }

                $model->updateAttributes(['image' => $albumImagePath]);

                $poolFiles->add($albumImagePath);

                $photo = new Photo($model->id);

                foreach ($model->photoImages as $file) {
                    $path = $photo->create($file);

                    $poolFiles->add($path);
                }

                $transaction->commit();

                return $this->redirect(['index']);

            } catch (\Exception $e) {
                $transaction->rollBack();

                $poolFiles->deleteFiles();

                throw new UserException($e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = StoriesAlbum::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
