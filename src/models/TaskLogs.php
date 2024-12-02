<?php

namespace hesabro\trello\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "tbl_task_logs".
 *
 * @property int $id
 * @property string $creator_id
 * @property string $task_id
 * @property int $old_status
 * @property int $new_status
 * @property int $status
 * @property string $created
 *
 * @property User $creator
 * @property TblProjectTask $task
 * @property TblProjectStatus $newStatus
 */
class TaskLogs extends \yii\db\ActiveRecord
{
    const STATUS_CREATE = 1;
    const STATUS_CHANGE= 2;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_task_logs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'new_status', 'status'], 'required'],
            [['creator_id', 'task_id', 'old_status', 'new_status', 'status', 'created'], 'integer'],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['creator_id' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectTask::class, 'targetAttribute' => ['task_id' => 'id']],
            [['new_status'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectStatus::class, 'targetAttribute' => ['new_status' => 'id']],
            [['old_status'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectStatus::class, 'targetAttribute' => ['old_status' => 'id']],
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
            'new_status' => Yii::t('app', 'New Status'),
            'status' => Yii::t('app', 'Status'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getNewStatus()
    {
        return $this->hasOne(ProjectStatus::class, ['id' => 'new_status']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOldStatus()
    {
        return $this->hasOne(ProjectStatus::class, ['id' => 'old_status']);
    }

    /**
     * @inheritdoc
     * @return TaskLogsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TaskLogsQuery(get_called_class());
    }

    public static function Create($task_id, $old_status, $new_status, $log_status)
    {
        $model=new self();
        $model->task_id=$task_id;
        $model->old_status=$old_status;
        $model->new_status=$new_status;
        $model->status=$log_status;
        if($model->save())
        {
            return $model;
        }else
        {
            return false;
        }
    }

    public function getTitle()
    {
        if($this->status==self::STATUS_CREATE)
        {
            return Yii::t("app","Added This Task To ").$this->newStatus->title_status;

        }else
        {
            return Yii::t("app","Move This Task From ").$this->oldStatus->title_status.' '.Yii::t("app","To ").$this->newStatus->title_status;
        }
    }
    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->created = time();
            $this->creator_id = Yii::$app->user->id;
        }
        return parent::beforeSave($insert);
    }
}
