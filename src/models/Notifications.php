<?php

namespace hesabro\trello\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "tbl_notifications".
 *
 * @property int $id
 * @property string $creator_id
 * @property string $update_id
 * @property string $project_id
 * @property string $task_id
 * @property string $title_not
 * @property int $status
 * @property string $created
 * @property string $changed
 *
 * @property User $creator
 * @property User $update
 * @property ProjectTask $task
 * @property Project $project
 */
class Notifications extends \yii\db\ActiveRecord
{
    const STATUS_VIEWED = 1;// مشاهده شده
    const STATUS_NEW = 0; // جدید
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_notifications';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'task_id', 'title_not'], 'required'],
            [['creator_id', 'update_id', 'project_id', 'task_id', 'status', 'created', 'changed'], 'integer'],
            [['title_not'], 'string', 'max' => 48],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['creator_id' => 'id']],
            [['update_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['update_id' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectTask::class, 'targetAttribute' => ['task_id' => 'id']],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::class, 'targetAttribute' => ['project_id' => 'id']],
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
            'project_id' => Yii::t('app', 'Project ID'),
            'task_id' => Yii::t('app', 'Task ID'),
            'title_not' => Yii::t('app', 'Title Not'),
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
    public function getUpdate()
    {
        return $this->hasOne(User::class, ['id' => 'update_id']);
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
    public function getProject()
    {
        return $this->hasOne(Project::class, ['id' => 'project_id']);
    }

    /**
     * @inheritdoc
     * @return NotificationsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NotificationsQuery(get_called_class());
    }

/*
 * ثبت ناتیفیکیشن هنگام انتقال یک تسک
 */
    public static function MoveTask($project_id, $task_id, $title)
    {
        $task_watches=TaskWatches::find()->findByTask($task_id)->andwhere('creator_id<>:UserId',[':UserId'=>Yii::$app->user->id])->all();// فقط تسک هایی که توسط کاربران دیگر واچز شده اند
        foreach ($task_watches as $watche)
        {
            $notification=new self();
            $notification->project_id=$project_id;
            $notification->task_id=$task_id;
            $notification->title_not=$title;
            $notification->creator_id=$watche->creator_id;// کاربری که این ناتیفیکیشن برای آن نمایش داده می شود
            $notification->update_id = Yii::$app->user->id; // کاربری که این ناتیفیکیشن را ایجاد کرده است
            $notification->changed = time();
            $notification->save();
        }
    }

    /*
     * تغیر وضعیت تمام نات های این تسک به حالت مشاهده شده
     * برای کاربر جاری
     */
    public static function MarkAsView($task_id, $user_id)
    {
        self::updateAll(['status' => self::STATUS_VIEWED],
            'task_id = :TaskId AND creator_id = :UserId AND status=:Status',
            [
                ':TaskId'=>$task_id,
                ':UserId'=>$user_id,
                ':Status'=>self::STATUS_NEW
            ]
        );
    }

    /*
     * getAll
     */
    public static function getMenuItem()
    {
        return self::find()->my(Yii::$app->user->id)->all();
    }
    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->created = time();
            $this->status= self::STATUS_NEW;
        }

        return parent::beforeSave($insert);
    }
}
