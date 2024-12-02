<?php

namespace hesabro\trello\models;

/**
 * This is the ActiveQuery class for [[TaskWatches]].
 *
 * @see TaskWatches
 */
class TaskWatchesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TaskWatches[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TaskWatches|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }


    public function findByTask($task_id)
    {
        return $this->andWhere('task_id=:TaskId',[':TaskId'=>$task_id]);
    }


    public function findByCreator($creator_id)
    {
        return $this->andWhere('creator_id=:CreatorId',[':CreatorId'=>$creator_id]);
    }
}
