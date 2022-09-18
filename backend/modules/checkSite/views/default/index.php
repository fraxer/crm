<?php

use yii\helpers\Html;
use yii\base\View;
use backend\modules\checkSite\assets\ModuleAsset;

$this->title = Yii::t('index', 'title');
$this->params['breadcrumbs'][] = $this->title;
ModuleAsset::register($this);
?>
<div class="check-site-default-index">
    <?= Html::a(Yii::t('index', 'create'), ['create'], ['class' => 'btn btn-primary']) ?>

    <div class="table">
        <div class="head">
            <div class="column column_color"></div>
            <div class="column column_domain"><?= \Yii::t('index', 'site') ?></div>
            <div class="column column_checked_at"><?= \Yii::t('index', 'checked') ?></div>
            <div class="column column_status"><?= \Yii::t('index', 'status') ?></div>
            <div class="column column_actions"></div>
        </div>
        <?= View::render('list-items', ['items' => $items]) ?>
    </div>
</div>
