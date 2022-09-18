<?php

use yii\helpers\Html;
use yii\base\View;

?>

<div class="item">
    <div class="column column_color"></div>
    <div class="column column_domain">
        <?= Html::a($model->domain, ['update', 'id' => $model->id]) ?>
    </div>
    <div class="column column_checked_at"><?= date('d.m.Y H:i', strtotime($model->actualStatus->checked_at)) ?></div>
    <div class="column column_status"><?= $model->actualStatus->status ?></div>
    <div class="column column_actions">
        <?= Html::a(Yii::t('index', 'attach'), ['create', 'parent_id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('index', 'update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('index', 'delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('index', !count($model->childs) ? 'confirm_delete_domain' : 'confirm_delete_section'),
                'method' => 'post',
            ],
        ]) ?>
        <div class="dropdown" onclick="<?= $isSection ? 'toggleSection(event)' : 'toggleDomain(event)' ?>"></div>
    </div>
</div>
           