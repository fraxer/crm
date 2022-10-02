<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\stories\models\StoriesPhoto */
/* @var $form ActiveForm */
?>
<div class="photo-index">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <?= $form->field($model, 'album_id')->textInput(['type' => 'hidden']) ?>
        <?= $form->field($model, 'rank')->textInput() ?>
        <?= $form->field($model, 'duration')->textInput() ?>
        <?= $form->field($model, 'imageFile')->fileInput() ?>
    
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- photo-index -->
