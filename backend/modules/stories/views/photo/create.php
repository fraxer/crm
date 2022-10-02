<?php

use yii\helpers\Html;

$this->title = 'Create Stories Photo';
$this->params['breadcrumbs'][] = ['label' => 'Stories Photos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stories-album-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
