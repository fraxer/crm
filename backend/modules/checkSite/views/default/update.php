<?php

use yii\helpers\Html;

$this->title = 'Update Domain: ' . $model->domain;
$this->params['breadcrumbs'][] = ['label' => 'Domains', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->domain;
?>
<div class="task-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
