<?php

use yii\helpers\Html;

$this->title = Yii::t('update', 'title') . $model->domain;
$this->params['breadcrumbs'][] = ['label' => Yii::t('update', 'section_title'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->domain;
?>
<div class="task-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
