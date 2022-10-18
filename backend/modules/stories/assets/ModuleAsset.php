<?php

namespace backend\modules\stories\assets;

use yii\web\AssetBundle;

class ModuleAsset extends AssetBundle 
{
    public $sourcePath = '@stories-assets';

    public $css = [
        'css/swiper-bundle.min.css',
        'css/style.css',
        'css/modal.css',
        'css/album-form.css',
    ];

    public $js = [
        'js/swiper-bundle.min.js',
        'js/main.js',
    ];
}  