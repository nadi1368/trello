<?php

namespace hesabro\trello\models;

/**
 * This is the ActiveQuery class for [[TaskFallow]].
 *
 * @see TaskFallow
 */
class TaskFallowQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TaskFallow[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TaskFallow|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
