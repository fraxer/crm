<?php

use yii\helpers\Html;
use backend\modules\stories\assets\ModuleAsset;

/* @var $this yii\web\View */
/* @var $model backend\modules\stories\models\StoriesAlbum */

$this->title = 'Update Stories Album: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Stories Albums', 'url' => ['index']];
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
