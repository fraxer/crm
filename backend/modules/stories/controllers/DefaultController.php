<?php

namespace backend\modules\stories\controllers;

use Yii;
use backend\modules\stories\models\StoriesAlbum;
use backend\modules\stories\models\StoriesForm;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DefaultController implements the CRUD actions for StoriesAlbum model.
 */
class DefaultController extends Controller
{
    /**
     * @inheritDoc
     */
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

    /**
     * Lists all StoriesAlbum models.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'albums' => StoriesAlbum::find()->orderBy('id desc')->all(),
        ]);
    }

    /**
     * Displays a single StoriesAlbum model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new StoriesAlbum model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $modelForm = new StoriesForm();

        if ($this->request->isPost) {
            $modelForm->imageFile = UploadedFile::getInstance($modelForm, 'imageFile');

            if (!$modelForm->upload()) {
                throw new NotFoundHttpException('Error opload album image');
            }

            $modelAlbum = new StoriesAlbum();
            $modelAlbum->attributes = $this->request->post('StoriesForm');
            $modelAlbum->image = $modelForm->getFilePath();

            if ($modelAlbum->save()) {
                return $this->redirect(['index']);
            } else {
                throw new NotFoundHttpException('cant post');
            }
        }

        return $this->render('create', [
            'model' => $modelForm,
        ]);
    }

    /**
     * Updates an existing StoriesAlbum model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
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

    /**
     * Deletes an existing StoriesAlbum model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the StoriesAlbum model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return StoriesAlbum the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StoriesAlbum::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
