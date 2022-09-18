<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use backend\modules\checkSite\models\CheckSite;

$items = ArrayHelper::map(CheckSite::find()->select(['id', 'domain'])->where(['<>', 'id', $model->id])->all(), 'id', 'domain');

$params = [
    'prompt' => 'Root'
];

$periodChecking = [
    10 => '10 min',
    20 => '20 min',
    30 => '30 min',
    60 => '1 hour',
    120 => '2 hour',
    240 => '3 hour',
]

?>

<div class="task-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'parent_id')->dropDownList($items, $params)->label('Parent zone') ?>

    <?= $form->field($model, 'domain')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'period_checking')->dropDownList($periodChecking)->label('Period of checking (min)') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
