<?php

namespace hesabro\trello\models;

/**
 * This is the ActiveQuery class for [[Comments]].
 *
 * @see Comments
 */
class CommentsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Comments[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Comments|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }


    public function active()
    {
        return $this->andWhere(['<>','status', Comments::STATUS_DELETED]);
    }


    public function deActive()
    {
        return $this->andWhere(['=','status', Comments::STATUS_DELETED]);
    }


    public function findByTask($task_id)
    {
        return $this->andWhere('task_id=:TaskId',[':TaskId'=>$task_id]);
    }
}
