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

    <div class="salons">
        <?= $formAlbum->field($model, 'salon_id')->radioList($salons)->label(false) ?>
    </div>

    <?= $formAlbum->field($model, 'name')->textInput(['maxlength' => true])->label(Yii::t('form', 'album_name')) ?>

    <?= $formAlbum->field($model, 'rank')->textInput()->label(Yii::t('form', 'album_rank')) ?>

    <?= $formAlbum->field($model, 'albumImage', ['template' => '{label}<br>{input}{error}{hint}'])->fileInput()->label(Yii::t('form', 'album_image')) ?>

    <?php if (!empty($model->image)) { ?>
        <img src="<?= $model->image ?>" width="120" height="120" class="album-image">
    <?php } ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Delete', ['default/delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('form', 'table_confirm_delete'),
                'method' => 'post',
            ],
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<hr>
<div class="stories-photo-form">

    <h2><?= Yii::t('form', 'photo_title') ?></h2>

    <?php $formPhotos = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $formPhotos->field($model, 'photoImages[]', ['template' => '{label}<br>{input}{error}{hint}'])->fileInput(['multiple' => true])->label(Yii::t('form', 'photo_add')) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('form', 'photo_upload'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php if (count($model->photos)) { ?>

        <div class="table">
            <div class="head">
                <div class="col col_image"><?= Yii::t('form', 'table_image') ?></div>
                <div class="col col_rank"><?= Yii::t('form', 'table_rank') ?></div>
                <div class="col col_duration"><?= Yii::t('form', 'table_duration') ?></div>
                <div class="col col_actions"><?= Yii::t('form', 'table_actions') ?></div>
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
                    <?= Html::submitButton(Yii::t('form', 'table_save'), ['class' => 'btn btn-success']) ?>
                    <?= Html::a(Yii::t('form', 'table_delete'), ['photo/delete', 'id' => $photoModel->id], [
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
