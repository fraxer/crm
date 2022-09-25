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
    10 => '10 ' . Yii::t('form', 'minute'),
    20 => '20 ' . Yii::t('form', 'minute'),
    30 => '30 ' . Yii::t('form', 'minute'),
    60 => '1 ' . Yii::t('form', 'hour'),
    120 => '2 ' . Yii::t('form', 'hour'),
    240 => '3 ' . Yii::t('form', 'hour'),
];

?>

<div class="task-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'parent_id')->dropDownList($items, $params)->label(Yii::t('form', 'parent_zone')) ?>

    <?= $form->field($model, 'domain')->textInput(['maxlength' => true])->label(Yii::t('form', 'domain')) ?>

    <?= $form->field($model, 'period_checking')->dropDownList($periodChecking)->label(Yii::t('form', 'period_checking')) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('form', 'save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
