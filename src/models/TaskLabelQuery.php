<?php

namespace hesabro\trello\models;

/**
 * This is the ActiveQuery class for [[TaskLabel]].
 *
 * @see TaskLabel
 */
class TaskLabelQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TaskLabel[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TaskLabel|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function active()
    {
        return $this->andWhere(['<>','status', TaskLabel::STATUS_DELETED]);
    }


    public function deActive()
    {
        return $this->andWhere(['=','status', TaskLabel::STATUS_DELETED]);
    }


    public function findByTask($task_id)
    {
        return $this->andWhere('task_id=:TaskId',[':TaskId'=>$task_id]);
    }


    public function findByLabel($label_id)
    {
        return $this->andWhere('label_id=:LabelId',[':LabelId'=>$label_id]);
    }


}
