<?php
namespace hesabro\trello\component;


class AjaxFilter extends \yii\base\ActionFilter
{
    public function beforeAction($action)
    {
        if (Yii::$app->request->isAjax) {

            return parent::beforeAction($action);
        }

        throw new BadRequestHttpException();
    }
}