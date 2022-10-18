<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$salons = ArrayHelper::map($salons, 'id', 'name');

?>

<div class="stories-album-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="salons">
        <?= $form->field($model, 'salon_id')->radioList($salons, [
            'item' => function ($index, $label, $name, $checked, $value) {
                if ($index == 0) $checked = true;

                $radio = Html::radio($name, $checked, ['value' => $value, 'label' => $label]);

                return Html::tag('label', $radio);
            },
        ])->label(false) ?>
    </div>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label(Yii::t('form', 'album_name')) ?>

    <?= $form->field($model, 'rank')->textInput()->label(Yii::t('form', 'album_rank')) ?>

    <?= $form->field($model, 'albumImage', ['template' => '{label}<br>{input}{error}{hint}'])->fileInput()->label(Yii::t('form', 'album_image')) ?>

    <?= $form->field($model, 'photoImages[]', ['template' => '{label}<br>{input}{error}{hint}'])->fileInput(['multiple' => true])->label(Yii::t('form', 'photo_images')) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('form', 'album_save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
