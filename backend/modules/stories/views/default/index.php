<?php

use yii\helpers\Html;
use yii\helpers\Url;
use backend\modules\stories\assets\ModuleAsset;

$this->title = Yii::t('index', 'title');
$this->params['breadcrumbs'][] = $this->title;
ModuleAsset::register($this);
?>
<div class="stories-album-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!Yii::$app->user->isGuest) { ?>
        <div>
            <?= Html::a(Yii::t('index', 'album_create'), ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <br>
    <?php } ?>

    <div class="albums">
        <?php foreach ($albums as $idx => $album) { ?>
            <div class="album">
                <div class="album__circle">
                    <img src="<?= $album->image ?>" alt="<?= $album->name ?>" onclick="modal.open(<?= $idx ?>)" class="album__image">
                    <?php if (!Yii::$app->user->isGuest) { ?>
                    <div class="album__controls">
                        <div class="album__open" onclick="modal.open(<?= $idx ?>)" title="<?= Yii::t('index', 'show') ?>">
                            <svg width="17px" height="17px" viewBox="0 0 23.758 23.758" class="album__icon">
                                <path d="M23.456,10.616c-3.426-4.5-7.224-6.782-11.287-6.782c-6.828,0-11.681,6.527-11.885,6.807 c-0.381,0.522-0.379,1.226,0.006,1.748c3.7,5.001,7.64,7.535,11.712,7.535c6.93,0,11.362-7.347,11.547-7.66 C23.858,11.745,23.82,11.101,23.456,10.616z M12.003,16.97c-2.851,0-5.753-1.824-8.634-5.423c1.44-1.611,4.774-4.759,8.801-4.759 c2.854,0,5.637,1.611,8.278,4.792C19.257,13.205,16.056,16.97,12.003,16.97z"/>
                                <path d="M11.881,9.036c-1.578,0-2.86,1.276-2.86,2.843c0,1.568,1.283,2.843,2.86,2.843 c1.575,0,2.856-1.275,2.856-2.843C14.737,10.312,13.456,9.036,11.881,9.036z M11.076,10.506l0.68,1.338l-1.411-0.841 L11.076,10.506z"/>
                            </svg>
                        </div>

                        <a href="<?= Url::toRoute(['default/update', 'id' => $album->id]) ?>" class="album__edit" title="<?= Yii::t('index', 'edit') ?>">
                            <svg width="15px" height="15px" viewBox="0 0 20 20" class="album__icon">
                                <path d="M12.3 3.7l4 4L4 20H0v-4L12.3 3.7zm1.4-1.4L16 0l4 4-2.3 2.3-4-4z"/>
                            </svg>
                        </a>
                    </div>
                    <?php } ?>
                </div>
                <div class="album__name"><?= $album->name ?></div>
            </div>
        <?php } ?>
    </div>
</div>

<div class="stories-modal">
    <div class="stories-modal__substrate"></div>
    <div class="stories-modal__window">
        <div class="stories-modal__header">
            <div class="stories-modal__lines"></div>
            <div class="stories-modal__album">
                <img src="" class="stories-modal__album-image">
                <span class="stories-modal__album-name"></span>
            </div>
            <div class="stories-modal__close" onclick="modal.close()">
                <svg width="24px" height="24px" viewBox="0 0 512 512">
                    <path d="M443.6,387.1L312.4,255.4l131.5-130c5.4-5.4,5.4-14.2,0-19.6l-37.4-37.6c-2.6-2.6-6.1-4-9.8-4c-3.7,0-7.2,1.5-9.8,4 L256,197.8L124.9,68.3c-2.6-2.6-6.1-4-9.8-4c-3.7,0-7.2,1.5-9.8,4L68,105.9c-5.4,5.4-5.4,14.2,0,19.6l131.5,130L68.4,387.1 c-2.6,2.6-4.1,6.1-4.1,9.8c0,3.7,1.4,7.2,4.1,9.8l37.4,37.6c2.7,2.7,6.2,4.1,9.8,4.1c3.5,0,7.1-1.3,9.8-4.1L256,313.1l130.7,131.1 c2.7,2.7,6.2,4.1,9.8,4.1c3.5,0,7.1-1.3,9.8-4.1l37.4-37.6c2.6-2.6,4.1-6.1,4.1-9.8C447.7,393.2,446.2,389.7,443.6,387.1z" fill="#fff"/>
                </svg>
            </div>
        </div>
        <div class="stories-modal__slider">
            <div class="swiper modalSlider">
                <div class="swiper-wrapper"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </div>
</div>

<script>const albumJson = <?= $albumsJson ?></script>