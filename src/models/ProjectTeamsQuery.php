<?php

namespace hesabro\trello\models;

/**
 * This is the ActiveQuery class for [[ProjectTeams]].
 *
 * @see ProjectTeams
 */
class ProjectTeamsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ProjectTeams[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ProjectTeams|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function active()
    {
        return $this->andWhere(['<>','status', ProjectTeams::STATUS_DELETED]);
    }


    public function deActive()
    {
        return $this->andWhere(['=','status', ProjectTeams::STATUS_DELETED]);
    }


    public function findByProject($project_id)
    {
        return $this->andWhere('project_id=:Project',[':Project'=>$project_id]);
    }
}
