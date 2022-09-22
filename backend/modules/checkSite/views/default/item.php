<?php

use yii\helpers\Html;
use yii\base\View;
use yii\helpers\Url;

?>

<div class="item">
    <div class="data">
        <div class="column column_color">
            <?php if ($isSection) { ?>
                <div class="color <?= $model->sectionStatus == 200 ? 'color_green' : 'color_red' ?>"></div>
            <?php } else { ?>
                <div class="color <?= $model->actualStatus->status == 200 ? 'color_green' : 'color_red' ?>"></div>
            <?php } ?>
        </div>
        <div class="column column_domain">
            <a href="https://<?= $model->domain ?>" target="_blank"><?= $model->domain ?></a>
        </div>
        <div class="column column_checked_at"><?= date('d.m.Y H:i', strtotime($model->actualStatus->checked_at)) ?></div>
        <div class="column column_status"><?= $isSection ? $model->sectionStatus : $model->actualStatus->status ?></div>
        <div class="column column_actions">
            <a href="<?= Url::toRoute(['create', 'parent_id' => $model->id]) ?>" class="link" title="<?= Yii::t('index', 'create_subdomain') ?>">
                <svg width="17px" height="17px" viewBox="0 0 34.398 34.398">
                    <path d="M17.202,0C10.749,0,5.515,5.197,5.515,11.607c0,3.281,3.218,9.156,3.218,9.156l8.039,13.635l8.386-13.477 c0,0,3.726-5.605,3.726-9.314C28.883,5.197,23.653,0,17.202,0z M17.147,18.002c-3.695,0-6.688-2.994-6.688-6.693 c0-3.693,2.993-6.686,6.688-6.686c3.693,0,6.69,2.992,6.69,6.686C23.837,15.008,20.84,18.002,17.147,18.002z"/>
                    <polygon points="18.539,7.233 15.898,7.233 15.898,10.242 12.823,10.242 12.823,12.887 15.898,12.887 15.898,15.985 18.539,15.985 18.539,12.887 21.576,12.887 21.576,10.242 18.539,10.242"/>
                </svg>
            </a>
            <a href="<?= Url::toRoute(['update', 'id' => $model->id]) ?>" class="link" title="<?= Yii::t('index', 'update') ?>">
                <svg width="17px" height="17px" viewBox="0 0 20 20">
                    <path d="M12.3 3.7l4 4L4 20H0v-4L12.3 3.7zm1.4-1.4L16 0l4 4-2.3 2.3-4-4z"/>
                </svg>
            </a>
            <a href="<?= Url::toRoute(['delete', 'id' => $model->id]) ?>" data-confirm="<?= Yii::t('index', !count($model->childs) ? 'confirm_delete_domain' : 'confirm_delete_section') ?>" data-method="post" class="link" title="<?= Yii::t('index', 'delete') ?>">
                <svg width="17px" height="17px" viewBox="0 0 512 512">
                    <path d="M432,96h-48V32c0-17.672-14.328-32-32-32H160c-17.672,0-32,14.328-32,32v64H80c-17.672,0-32,14.328-32,32v32h416v-32 C464,110.328,449.672,96,432,96z M192,96V64h128v32H192z"/>
                    <path d="M80,480.004C80,497.676,94.324,512,111.996,512h288.012C417.676,512,432,497.676,432,480.008v-0.004V192H80V480.004z M320,272c0-8.836,7.164-16,16-16s16,7.164,16,16v160c0,8.836-7.164,16-16,16s-16-7.164-16-16V272z M240,272 c0-8.836,7.164-16,16-16s16,7.164,16,16v160c0,8.836-7.164,16-16,16s-16-7.164-16-16V272z M160,272c0-8.836,7.164-16,16-16 s16,7.164,16,16v160c0,8.836-7.164,16-16,16s-16-7.164-16-16V272z"/>
                </svg>
            </a>
            <div class="dropdown" onclick="<?= $isSection ? 'toggleSection(event)' : 'toggleDomain(event)' ?>">
                <div class="dropdown__arrow"></div>
            </div>
        </div>
    </div>
    <?php if (!$isSection) { ?>
    <div class="statuses">
        <?php foreach ($model->statuses as $status) { ?>
            <div class="status">
                <div class="data">
                    <div class="column column_color"></div>
                    <div class="column column_domain">
                        <div class="circle <?= $status->status == 200 ? 'circle_green' : 'circle_red' ?>"></div>
                    </div>
                    <div class="column column_checked_at"><?= date('d.m.Y H:i', strtotime($status->checked_at)) ?></div>
                    <div class="column column_status"><?= $status->status ?></div>
                    <div class="column column_actions"></div>
                </div>
            </div>
        <?php } ?>
    </div>
    <?php } ?>
</div>