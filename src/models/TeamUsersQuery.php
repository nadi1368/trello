<?php

namespace hesabro\trello\models;

/**
 * This is the ActiveQuery class for [[TeamUsers]].
 *
 * @see TeamUsers
 */
class TeamUsersQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TeamUsers[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TeamUsers|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function active()
    {
        return $this->andWhere(['<>','tbl_team_users.status', TeamUsers::STATUS_DELETED]);
    }


    public function deActive()
    {
        return $this->andWhere(['=','tbl_team_users.status', TeamUsers::STATUS_DELETED]);
    }

    public function findByAdmin()
    {
        return $this->andWhere('role=:Role',[':Role'=>TeamUsers::ROLE_ADMIN]);
    }

    public function findByUser($user_id)
    {
        return $this->andWhere('user_id=:User',[':User'=>$user_id]);
    }




}
