<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Task */

$this->title = 'Add site';
$this->params['breadcrumbs'][] = ['label' => 'Domains', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
