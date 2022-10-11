<?php

namespace backend\modules\stories\controllers;

use Yii;
use backend\modules\stories\models\StoriesAlbum;
use backend\modules\stories\models\StoriesPhoto;
use backend\modules\stories\models\StoriesSalon;
use backend\modules\stories\helpers\Album;
use backend\modules\stories\helpers\Photo;
use backend\modules\stories\helpers\PoolFiles;
use backend\modules\stories\helpers\FileStructure;
use backend\modules\stories\helpers\ImageThumbnail;
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
            'albums' => StoriesAlbum::find()->orderBy('rank asc')->all(),
        ]);
    }

    public function actionCreate()
    {
        $model = new StoriesAlbum();
        $model->scenario = StoriesAlbum::SCENARIO_CREATE;

        if ($this->request->isPost) {
            $poolFiles = new PoolFiles();

            $transaction = \Yii::$app->db->beginTransaction();

            try {
                Album::loadAndSave($model);

                Album::setRankOnCreate($model);

                $albumImagePath = Album::prepareFileStructure($model);

                Album::uploadAndCropImage($model, $albumImagePath);

                $poolFiles->add($albumImagePath);

                foreach ($model->photoImages as $file) {
                    $path = Photo::create($file, $model->id);

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

        $model->rank = Album::getCountAlbums() + 1;

        return $this->render('create', [
            'model' => $model,
            'salons' => StoriesSalon::find()->select(['id', 'name'])->all(),
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = StoriesAlbum::SCENARIO_UPDATE;

        if ($this->request->isPost) {

            $poolFiles = new PoolFiles();

            $transaction = \Yii::$app->db->beginTransaction();

            try {
                Album::load($model);
                Album::setRankOnUpdate($model);
                Album::save($model);

                if (!empty($model->albumImage)) {

                    $albumImagePath = Album::prepareFileStructure($model);

                    Album::deleteImageFile($model->image);

                    Album::uploadAndCropImage($model, $albumImagePath);

                    $poolFiles->add($albumImagePath);
                }

                if (!empty($model->photoImages)) {
                    foreach ($model->photoImages as $file) {
                        $path = Photo::create($file, $model->id);

                        $poolFiles->add($path);
                    }
                }

                $transaction->commit();

                return $this->redirect(['update', 'id' => $model->id]);

            }catch (\Exception $e) {
                $transaction->rollBack();

                $poolFiles->deleteFiles();

                throw new UserException($e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
            'salons' => StoriesSalon::find()->select(['id', 'name'])->all(),
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $albumId = $model->id;

        Album::delete($model);

        Album::updateRankAlbums($albumId);

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
