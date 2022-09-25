<?php

namespace backend\modules\checkSite;

use yii\base\BootstrapInterface;

class CheckSite extends \yii\base\Module implements BootstrapInterface
{
    public $controllerNamespace = 'backend\modules\checkSite\controllers';

    public function init()
    {
        parent::init();

        $this->setAliases([
            '@check-site-assets' => __DIR__ . '/assets'
        ]);

        \Yii::$app->language = 'ru';

        $this->registerTranslations();
    }

    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'backend\modules\checkSite\commands';
        }
    }

    public function registerTranslations()
    {
        \Yii::$app->i18n->translations['*'] = [
            'class'          => 'yii\i18n\PhpMessageSource',
            'basePath'       => '@backend/modules/checkSite/messages',
            'fileMap'        => [
                'index' => 'index.php',
                'create' => 'create.php',
                'update' => 'update.php',
                'form' => 'form.php',
                'item' => 'item.php',
                'controller' => 'controller.php',
            ],
        ];
    }
}
