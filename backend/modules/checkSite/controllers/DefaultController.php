<?php

namespace backend\modules\checkSite\controllers;

use backend\modules\checkSite\models\CheckSite;
use backend\modules\checkSite\models\CheckSiteStatus;
use backend\modules\checkSite\adapters\GroupAdapter;
use backend\modules\checkSite\commands\AvailabilityController;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\base\UserException;

class DefaultController extends Controller
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

    public function actionIndex()
    {
        $items = CheckSite::find()->all();

        return $this->render('index', [
            'items' => GroupAdapter::groupSites($items),
        ]);
    }

    public function actionCreate($parent_id = 0)
    {
        $model = new CheckSite();

        if ($this->request->isPost) {

            if ($model->load($this->request->post())) {

                \Yii::debug($model->domain);

                if ($this->domainAlreadyExist($model->domain)) {
                    throw new UserException(\Yii::t('controller', 'domain_already_exist'));
                }

                if ($model->save()) {
                    $console = new AvailabilityController('availability', \Yii::$app); 
                    $console->runAction('check');

                    return $this->redirect(['index']);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        $model->id = 0;
        $model->parent_id = $parent_id;

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->recursiveRemoveChilds($id);

        CheckSiteStatus::deleteAll(['site_id' => $id]);

        $model = $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = CheckSite::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('controller', 'domain_not_found'));
    }

    protected function domainAlreadyExist($domain)
    {
        if (CheckSite::findOne(['domain' => $domain]) !== null) {
            return true;
        }

        return false;
    }

    private function recursiveRemoveChilds($id)
    {
        $items = CheckSite::find()->where(['parent_id' => $id])->all();

        foreach ($items as $item) {
            $this->recursiveRemoveChilds($item->id);

            CheckSiteStatus::deleteAll(['site_id' => $item->id]);

            $item->delete();
        }
    }
}
