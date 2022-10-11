<?php

use yii\helpers\Html;
use backend\modules\stories\assets\ModuleAsset;

$this->title = 'Create Stories Album';
$this->params['breadcrumbs'][] = ['label' => 'Stories Albums', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
ModuleAsset::register($this);
?>
<div class="stories-album-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formCreate', [
        'model' => $model,
        'salons' => $salons,
    ]) ?>

</div>
