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

    <?= $form->field($model, 'imageFile')->fileInput() ?>

    <?php if (!empty($model->image)) { ?>
        <img src="<?= $model->image ?>">
    <?php } ?>

    <?= $form->field($model, 'rank')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::button('delete', ['class' => 'btn btn-warning']) ?>
    </div>

    <?php ActiveForm::end(); ?>


    <hr>

    <h2>Photos</h2>

    <?php if ($model->id) { ?>
        <?= Html::a('photo_add', ['photo/create', 'album_id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?php } ?>

    <br>
    <br>

    <?php foreach ($model->photos as $photo) { ?>
        <div class="" style="display: flex;">
            <div><?= $photo->id ?> -</div>
            <div><?= $photo->image ?> -</div>
            <div>del</div>
        </div>
    <?php } ?>

</div>
