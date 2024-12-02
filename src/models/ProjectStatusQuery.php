<?php

namespace hesabro\trello\models;

/**
 * This is the ActiveQuery class for [[ProjectStatus]].
 *
 * @see ProjectStatus
 */
class ProjectStatusQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ProjectStatus[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ProjectStatus|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }


    public function active()
    {
        return $this->andWhere(['<>','status', ProjectStatus::STATUS_DELETED]);
    }


    public function deActive()
    {
        return $this->andWhere(['=','status', ProjectStatus::STATUS_DELETED]);
    }


    public function findByProject($project_id)
    {
        return $this->andWhere('project_id=:ProjectId',[':ProjectId'=>$project_id]);
    }
}
