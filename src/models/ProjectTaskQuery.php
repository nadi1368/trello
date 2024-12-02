<?php

namespace hesabro\trello\models;

/**
 * This is the ActiveQuery class for [[ProjectTask]].
 *
 * @see ProjectTask
 */
class ProjectTaskQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ProjectTask[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ProjectTask|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    public function active()
    {
        return $this->andWhere(['<>','status', ProjectTask::STATUS_DELETED]);
    }


    public function deActive()
    {
        return $this->andWhere(['=','status', ProjectTask::STATUS_DELETED]);
    }


    public function findByList($list_id)
    {
        return $this->andWhere('list_id=:ListId',[':ListId'=>$list_id]);
    }
}
