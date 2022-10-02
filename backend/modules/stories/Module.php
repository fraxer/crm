<?php

namespace backend\modules\stories;

/**
 * stories module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\stories\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $this->setAliases([
            '@stories-assets' => __DIR__ . '/assets'
        ]);

        \Yii::$app->language = 'ru';

        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        \Yii::$app->i18n->translations['*'] = [
            'class'          => 'yii\i18n\PhpMessageSource',
            'basePath'       => '@backend/modules/stories/messages',
            'fileMap'        => [
                'index' => 'index.php',
            ],
        ];
    }
}
