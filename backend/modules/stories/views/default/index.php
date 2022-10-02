<?php

use yii\helpers\Html;
use yii\helpers\Url;
use backend\modules\stories\assets\ModuleAsset;

$this->title = 'Stories Albums';
$this->params['breadcrumbs'][] = $this->title;
ModuleAsset::register($this);
?>
<div class="stories-album-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Stories Album', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="albums">
        <?php foreach ($albums as $album) { ?>
            <div class="album">
                <div class="album__circle">
                    <img src="<?= $album->image ?>" alt="<?= $album->name ?>" class="album__image">
                    <div class="album__controls">
                        <div class="album__open" title="show">
                            <svg width="17px" height="17px" viewBox="0 0 23.758 23.758" class="album__icon">
                                <path d="M23.456,10.616c-3.426-4.5-7.224-6.782-11.287-6.782c-6.828,0-11.681,6.527-11.885,6.807 c-0.381,0.522-0.379,1.226,0.006,1.748c3.7,5.001,7.64,7.535,11.712,7.535c6.93,0,11.362-7.347,11.547-7.66 C23.858,11.745,23.82,11.101,23.456,10.616z M12.003,16.97c-2.851,0-5.753-1.824-8.634-5.423c1.44-1.611,4.774-4.759,8.801-4.759 c2.854,0,5.637,1.611,8.278,4.792C19.257,13.205,16.056,16.97,12.003,16.97z"/>
                                <path d="M11.881,9.036c-1.578,0-2.86,1.276-2.86,2.843c0,1.568,1.283,2.843,2.86,2.843 c1.575,0,2.856-1.275,2.856-2.843C14.737,10.312,13.456,9.036,11.881,9.036z M11.076,10.506l0.68,1.338l-1.411-0.841 L11.076,10.506z"/>
                            </svg>
                        </div>

                        <a href="<?= Url::toRoute(['default/update', 'id' => $album->id]) ?>" class="album__edit" title="edit">
                            <svg width="15px" height="15px" viewBox="0 0 20 20" class="album__icon">
                                <path d="M12.3 3.7l4 4L4 20H0v-4L12.3 3.7zm1.4-1.4L16 0l4 4-2.3 2.3-4-4z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="album__name"><?= $album->name ?></div>
            </div>
        <?php } ?>
    </div>
</div>
