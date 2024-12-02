<?php

namespace hesabro\trello\models;

/**
 * This is the ActiveQuery class for [[TaskAssignment]].
 *
 * @see TaskAssignment
 */
class TaskAssignmentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TaskAssignment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TaskAssignment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }


    public function active()
    {
        return $this->andWhere(['<>','status', TaskAssignment::STATUS_DELETED]);
    }


    public function deActive()
    {
        return $this->andWhere(['=','status', TaskAssignment::STATUS_DELETED]);
    }


    public function findByProject($project_id)
    {
        return $this->andWhere('project_id=:ProjectId',[':ProjectId'=>$project_id]);
    }


    public function findByTask($task_id)
    {
        return $this->andWhere('task_id=:TaskId',[':TaskId'=>$task_id]);
    }


    public function findByUser($user_id)
    {
        return $this->andWhere('user_id=:UserId',[':UserId'=>$user_id]);
    }
}
