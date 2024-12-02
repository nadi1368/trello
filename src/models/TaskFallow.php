<?php

namespace hesabro\trello\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "tbl_task_fallow".
 *
 * @property string $id
 * @property string $creator_id
 * @property string $update_id
 * @property string $task_id
 * @property string $comment
 * @property string $status
 * @property string $created
 * @property string $changed
 *
 * @property User $creator
 * @property ProjectTask $task
 * @property User $update
 */
class TaskFallow extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_task_fallow';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['creator_id', 'update_id', 'task_id', 'status', 'created', 'changed'], 'required'],
            [['creator_id', 'update_id', 'task_id', 'status', 'created', 'changed'], 'integer'],
            [['comment'], 'string'],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['creator_id' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectTask::class, 'targetAttribute' => ['task_id' => 'id']],
            [['update_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['update_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'creator_id' => Yii::t('app', 'Creator ID'),
            'update_id' => Yii::t('app', 'Update ID'),
            'task_id' => Yii::t('app', 'Task ID'),
            'comment' => Yii::t('app', 'Comment'),
            'status' => Yii::t('app', 'Status'),
            'created' => Yii::t('app', 'Created'),
            'changed' => Yii::t('app', 'Changed'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::class, ['id' => 'creator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(ProjectTask::class, ['id' => 'task_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdate()
    {
        return $this->hasOne(User::class, ['id' => 'update_id']);
    }

    /**
     * @inheritdoc
     * @return TaskFallowQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TaskFallowQuery(get_called_class());
    }
}
