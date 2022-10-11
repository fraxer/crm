<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$salons = ArrayHelper::map($salons, 'id', 'name');

?>

<div class="stories-album-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'salon_id')->radioList($salons, [
        'item' => function ($index, $label, $name, $checked, $value) {
            if ($index == 0) $checked = true;

            $radio = Html::radio($name, $checked, ['value' => $value, 'label' => $label]);

            return Html::tag('label', $radio);
        },
    ])->label(false) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'albumImage')->fileInput() ?>

    <?= $form->field($model, 'rank')->textInput() ?>

    <?= $form->field($model, 'photoImages[]')->fileInput(['multiple' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
