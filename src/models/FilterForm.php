<?php

namespace hesabro\trello\models;

use yii\base\Model;
use hesabro\trello\Module;

class FilterForm extends Model
{
    public $member = [];
    public $label = [];

    public function rules()
    {
        return [
            [['member', 'label'], 'each', 'rule' => ['string']], 
            [['member', 'label'], 'default', 'value' => []],
        ];
    }
    public function attributeLabels()
    {
        return [
            'member' => Module::t('module', 'Members'),
            'label' => Module::t('module', 'Labels'),
        ];
    }
}
