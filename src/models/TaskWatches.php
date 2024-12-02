<?php

namespace hesabro\trello\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "tbl_task_watches".
 *
 * @property int $id
 * @property string $creator_id
 * @property string $task_id
 * @property int $old_status
 * @property string $created
 *
 * @property User $creator
 * @property ProjectTask $task
 */
class TaskWatches extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_task_watches';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'old_status'], 'required'],
            [['creator_id', 'task_id', 'old_status', 'created'], 'integer'],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['creator_id' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectTask::class, 'targetAttribute' => ['task_id' => 'id']],
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
            'task_id' => Yii::t('app', 'Task ID'),
            'old_status' => Yii::t('app', 'Old Status'),
            'created' => Yii::t('app', 'Created'),
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
     * @inheritdoc
     * @return TaskWatchesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TaskWatchesQuery(get_called_class());
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->created = time();
            $this->creator_id = Yii::$app->user->id;
        }
        return parent::beforeSave($insert);
    }
}
