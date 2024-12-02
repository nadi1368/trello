<?php

namespace hesabro\trello\models;

/**
 * This is the ActiveQuery class for [[Notifications]].
 *
 * @see Notifications
 */
class NotificationsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Notifications[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Notifications|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /*
     * دیده نشده
     */
    public function notWatch()
    {
        return $this->andWhere('status=:Status',[':Status'=>Notifications::STATUS_NEW]);
    }


/*
 * مربوط به کاربر جاری
 */
    public function my($user_id)
    {
        return $this->andWhere('creator_id=:Creator',[':Creator'=>$user_id]);
    }
}
