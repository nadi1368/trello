<?php

namespace hesabro\trello\models;

/**
 * This is the ActiveQuery class for [[ProjectUser]].
 *
 * @see ProjectUser
 */
class ProjectUserQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ProjectUser[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ProjectUser|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function active()
    {
        return $this->andWhere('tbl_project_user.status=:Status',[':Status'=>ProjectUser::STATUS_ACTIVE]);
    }

    public function findByAdmin()
    {
        return $this->andWhere('role=:Role',[':Role'=>ProjectUser::ROLE_ADMIN]);
    }

    public function findByUser($user_id)
    {
        return $this->andWhere('user_id=:User',[':User'=>$user_id]);
    }

    public function findByProject($project_id)
    {
        return $this->andWhere('project_id=:Project',[':Project'=>$project_id]);
    }
}
