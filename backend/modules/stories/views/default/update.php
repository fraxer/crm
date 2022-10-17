<?php

use yii\helpers\Html;
use backend\modules\stories\assets\ModuleAsset;

$this->title = Yii::t('update', 'title') . ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('index', 'title'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name];
ModuleAsset::register($this);
?>
<div class="stories-album-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formUpdate', [
        'model' => $model,
        'salons' => $salons,
    ]) ?>

</div>
