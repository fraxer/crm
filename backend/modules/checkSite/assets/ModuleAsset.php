<?php

namespace backend\modules\checkSite\assets;

use yii\web\AssetBundle;

class ModuleAsset extends AssetBundle 
{
    public $sourcePath = '@check-site-assets';

    public $css = [
        'css/style.css',
    ];

    public $js = [
        'js/main.js'
    ];
}  