<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\stories\models\StoriesAlbum */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="stories-album-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'albumImage')->fileInput() ?>

    <?php if (!empty($model->image)) { ?>
        <img src="<?= $model->image ?>">
    <?php } ?>

    <?= $form->field($model, 'rank')->textInput() ?>

    <?= $form->field($model, 'photoImages[]')->fileInput(['multiple' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::button('delete', ['class' => 'btn btn-warning']) ?>
    </div>

    <?php ActiveForm::end(); ?>


    <hr>

    <?php if ($model->id) { ?>

        <h2>Photos</h2>

        <?= count($model->photos) ? '' : 'Upload photos' ?>

        <?php foreach ($model->photos as $photo) { ?>
            <div class="" style="display: flex;">
                <div><?= $photo->id ?> -</div>
                <div><?= $photo->image ?> -</div>
                <div>del</div>
            </div>
        <?php } ?>
    <?php } ?>

</div>
