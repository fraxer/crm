<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$duration = [
    1 => '1 ' . Yii::t('form', 'second'),
    2 => '2 ' . Yii::t('form', 'second'),
    3 => '3 ' . Yii::t('form', 'second'),
    4 => '4 ' . Yii::t('form', 'second'),
    5 => '5 ' . Yii::t('form', 'second'),
    6 => '6 ' . Yii::t('form', 'second'),
    7 => '7 ' . Yii::t('form', 'second'),
    8 => '8 ' . Yii::t('form', 'second'),
    9 => '9 ' . Yii::t('form', 'second'),
    10 => '10 ' . Yii::t('form', 'second'),
    15 => '15 ' . Yii::t('form', 'second'),
    20 => '20 ' . Yii::t('form', 'second'),
    30 => '30 ' . Yii::t('form', 'second'),
    40 => '40 ' . Yii::t('form', 'second'),
    50 => '50 ' . Yii::t('form', 'second'),
    60 => '1 ' . Yii::t('form', 'minute'),
];

$salons = ArrayHelper::map($salons, 'id', 'name');
?>

<div class="stories-album-form">

    <?php $formAlbum = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $formAlbum->field($model, 'salon_id')->radioList($salons)->label(false) ?>

    <?= $formAlbum->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $formAlbum->field($model, 'albumImage')->fileInput()->label('Replace image') ?>

    <?php if (!empty($model->image)) { ?>
        <img src="<?= $model->image ?>" width="120" height="120">
    <?php } ?>

    <?= $formAlbum->field($model, 'rank')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Delete', ['default/delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete album?',
                'method' => 'post',
            ],
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>


    <hr>
    <br>

    <h2>Photos</h2>

    <?php $formPhotos = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $formPhotos->field($model, 'photoImages[]')->fileInput(['multiple' => true])->label('Add photos') ?>

    <div class="form-group">
        <?= Html::submitButton('Upload', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php if (count($model->photos)) { ?>

        <div class="table">
            <div class="head">
                <div class="col col_image">Image</div>
                <div class="col col_rank">Rank</div>
                <div class="col col_duration">Duration (s)</div>
                <div class="col col_actions">Actions</div>
            </div>
            <?php foreach ($model->photos as $photoModel) { ?>

                <?php $formPhoto = ActiveForm::begin(['action' => ['photo/update', 'id' => $photoModel->id], 'options' => ['class' => 'row']]); ?>

                <div class="col col_image">
                    <img src="<?= $photoModel->thumb ?>">
                </div>
                <div class="col col_rank">
                    <?= $formPhoto->field($photoModel, 'rank')->textInput()->label(false) ?>
                </div>
                <div class="col col_duration">
                    <?= $formPhoto->field($photoModel, 'duration')->dropDownList($duration)->label(false) ?>
                </div>
                <div class="col col_actions">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                    <?= Html::a('Delete', ['photo/delete', 'id' => $photoModel->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete photo?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>

                <?php ActiveForm::end(); ?>

            <?php } ?>
        </div>

    <?php } else { ?>
        Upload photos
    <?php } ?>

</div>
