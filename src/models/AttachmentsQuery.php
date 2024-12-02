<?php

namespace hesabro\trello\models;

/**
 * This is the ActiveQuery class for [[Attachments]].
 *
 * @see Attachments
 */
class AttachmentsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Attachments[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Attachments|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    public function active()
    {
        return $this->andWhere(['<>','status', Attachments::STATUS_DELETED]);
    }


    public function deActive()
    {
        return $this->andWhere(['=','status', Attachments::STATUS_DELETED]);
    }


    public function findByTask($task_id)
    {
        return $this->andWhere('task_id=:TaskId',[':TaskId'=>$task_id]);
    }
}
