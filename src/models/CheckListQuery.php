<?php

namespace hesabro\trello\models;

/**
 * This is the ActiveQuery class for [[CheckList]].
 *
 * @see CheckList
 */
class CheckListQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CheckList[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CheckList|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function active()
    {
        return $this->andWhere(['<>','status', CheckList::STATUS_DELETED]);
    }


    public function deActive()
    {
        return $this->andWhere(['=','status', CheckList::STATUS_DELETED]);
    }


    public function findByTask($task_id)
    {
        return $this->andWhere('task_id=:TaskId',[':TaskId'=>$task_id]);
    }
}
