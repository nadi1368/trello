<?php

namespace hesabro\trello\models;

/**
 * This is the ActiveQuery class for [[CheckListItem]].
 *
 * @see CheckListItem
 */
class CheckListItemQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CheckListItem[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CheckListItem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function done()
    {
        return $this->andWhere('status=:Status',[':Status'=>CheckListItem::STATUS_DONE]);
    }
}
