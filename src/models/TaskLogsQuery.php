<?php

namespace hesabro\trello\models;

/**
 * This is the ActiveQuery class for [[TaskLogs]].
 *
 * @see TaskLogs
 */
class TaskLogsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TaskLogs[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TaskLogs|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
