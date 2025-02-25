<?php

namespace hesabro\trello;


use Yii;
/**
 * trello module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'hesabro\trello\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }


    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('hesabro/trello/' . $category, $message, $params, $language);
    }
}
