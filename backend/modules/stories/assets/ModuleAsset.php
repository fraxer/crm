<?php

namespace backend\modules\stories\assets;

use yii\web\AssetBundle;

class ModuleAsset extends AssetBundle 
{
    public $sourcePath = '@stories-assets';

    public $css = [
        'css/style.css',
    ];

    public $js = [
        'js/main.js'
    ];
}  