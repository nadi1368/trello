<?php

namespace hesabro\trello\models;

use Yii;
use yii\helpers\Url;
use common\models\User;
use Exception;
use yii\db\Expression;

/**
 * This is the model class for table "tbl_project_task".
 *
 * @property string $id
 * @property string $creator_id
 * @property string $update_id
 * @property int $start
 * @property int $end
 * @property string $title_task
 * @property int $t_order
 * @property int $list_id
 * @property string $created
 * @property string $changed
 *
 * @property Attachments[] $tblAttachments
 * @property CheckList[] $tblCheckLists
 * @property Comments[] $tblComments
 * @property Notifications[] $tblNotifications
 * @property User $creator
 * @property User $update
 * @property ProjectStatus $status0
 * @property TaskAssignment[] $tblTaskAssignments
 * @property TaskFallow[] $tblTaskFallows
 * @property TaskLabel[] $tblTaskLabels
 * @property TaskLogs[] $tblTaskLogs
 * @property TaskWatches[] $tblTaskWatches
 */
class ProjectTask extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_project_task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title_task', 'list_id'], 'required'],
            [['creator_id', 'update_id', 'start', 'end', 't_order', 'list_id', 'status', 'created', 'changed'], 'integer'],
            [['title_task'], 'string', 'max' => 256],
            [['desc_task'], 'string'],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['creator_id' => 'id']],
            [['update_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['update_id' => 'id']],
            [['list_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectStatus::class, 'targetAttribute' => ['list_id' => 'id']],
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
            'start' => Yii::t('app', 'Start'),
            'end' => Yii::t('app', 'End'),
            'title_task' => Yii::t('app', 'Title Task'),
            'desc_task' => Yii::t('app', 'Description Task'),
            't_order' => Yii::t('app', 'T Order'),
            'status' => Yii::t('app', 'Status'),
            'created' => Yii::t('app', 'Created'),
            'changed' => Yii::t('app', 'Changed'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttachments()
    {
        return $this->hasMany(Attachments::class, ['task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCheckLists()
    {
        return $this->hasMany(CheckList::class, ['task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comments::class, ['task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotifications()
    {
        return $this->hasMany(Notifications::class, ['task_id' => 'id']);
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
    public function getList()
    {
        return $this->hasOne(ProjectStatus::class, ['id' => 'list_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskAssignments()
    {
        return $this->hasMany(TaskAssignment::class, ['task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskFallows()
    {
        return $this->hasMany(TaskFallow::class, ['task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskLabels()
    {
        return $this->hasMany(TaskLabel::class, ['task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskLogs()
    {
        return $this->hasMany(TaskLogs::class, ['task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskWatches()
    {
        return $this->hasMany(TaskWatches::class, ['task_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return ProjectTaskQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProjectTaskQuery(get_called_class());
    }

    protected function getCountItem($list_id)
    {
        return self::find()->findByList($list_id)->count();
    }
    /*
       * حذف منطقی
       */
    public function softDelete()
    {
        $this->status= self::STATUS_DELETED;
        if($this->save())
        {
            return true;
        }else
        {
            return false;
        }
    }

    /*
     * فعال کردن
     */
    public function restore()
    {
        $this->status= self::STATUS_ACTIVE;
        if($this->save())
        {
            return true;
        }else
        {
            return false;
        }
    }

    /*
     * تغیر ترتیب نمایش
     * تغیر موقعیت درون همون لیست
     */

    public function move($new_pos)
    {
        $old_pos_real=$this->t_order;//self::find()->active()->andWhere('t_order<:ThisOrder',[':ThisOrder'=>$this->t_order])->count()+1;// موقعیت این لیست با توجه به لیست های فعال قبل از آن
        $new_pos_real=self::find()->findByList($this->list_id)->active()->orderBy('t_order')->offset($new_pos-1)->limit(1)->one()->t_order;
        $this->t_order=$new_pos_real;
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if($flag = $this->save())
            {
                if($new_pos_real<$old_pos_real)
                {
                    $flag = self::updateAll(['t_order' => new Expression('t_order + (1)')],
                        'list_id=:List AND t_order>=:NewPos AND t_order<:OldPos AND id<>:ThisId',[':List'=>$this->list_id, ':NewPos'=>$new_pos_real, ':OldPos'=>$old_pos_real, ':ThisId'=>$this->id]);

                }else
                {
                    $flag = self::updateAll(['t_order' => new Expression('t_order - (1)')],
                        'list_id=:List AND t_order<=:NewPos AND t_order>:OldPos AND id<>:ThisId',[':List'=>$this->list_id, ':NewPos'=>$new_pos_real, ':OldPos'=>$old_pos_real, ':ThisId'=>$this->id]);

                }
            }
            if ($flag) {
                $transaction->commit();
                return true;
            }else
            {
                $transaction->rollBack();
                return false;
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage() . $e->getTraceAsString(),  __METHOD__ . ':' . __LINE__);
            $transaction->rollBack();
            return false;
        }
    }

    /*
     * تغیر وضعیت
     * انتقال به لیست دیگر
     */

    public function changeStatus($new_pos,$new_status)
    {

        $old_status=$this->list_id;

        $old_pos_real=$this->t_order;//self::find()->active()->andWhere('t_order<:ThisOrder',[':ThisOrder'=>$this->t_order])->count()+1;// موقعیت این لیست با توجه به لیست های فعال قبل از آن

        $before_item=self::find()->findByList($new_status)->active()->orderBy('t_order')->offset($new_pos-1)->limit(1)->one();
        $new_pos_real=$before_item ? $before_item->t_order : 1;

        $this->t_order=$new_pos_real;
        $this->list_id=$new_status;
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if($flag = $this->save())
            {
                /*
                 * اپدیت ترتیب نمایش تسک های موجود در لیست قبلی
                 * تمام تسک هایی که قبل از این تسک قرار دارند
                 */
                self::updateAll(['t_order' => new Expression('t_order - (1)')],
                    'list_id=:List AND  t_order>:OldPos ',[':List'=>$old_status, ':OldPos'=>$old_pos_real]);

                /*
                 * اپدیت ترتیب نمایش تسک های موجود در لیست جدید
                 * با توجه به موقعیت این تسک
                 */

                self::updateAll(['t_order' => new Expression('t_order + (1)')],
                        'list_id=:List AND t_order>=:NewPos AND id<>:ThisId',[':List'=>$this->list_id, ':NewPos'=>$new_pos_real, ':ThisId'=>$this->id]);

                /*
                 * ثبت لاگ
                 * برای انتقال از لیست
                 */
                $log_model=TaskLogs::Create($this->id, $old_status, $new_status,TaskLogs::STATUS_CHANGE);

                /*
                 * ثبت ناتیفیکیشن
                 * برای کاربرانی که این تسک را واچز کرده اند
                 */
                Notifications::MoveTask($this->list->project_id, $this->id, $log_model->getTitle());
            }
            if ($flag) {
                $transaction->commit();
                return true;
            }else
            {
                $transaction->rollBack();
                return false;
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage() . $e->getTraceAsString(),  __METHOD__ . ':' . __LINE__);
            $transaction->rollBack();
            return false;
        }

    }
/*
 * نمایش مهلت زمانی
 * در صفحه اصلی و جزئیات هر تسک
 */

    public function getDueDate($action="index")
    {
        if($this->end<=time())
        {
            $css_class='label-danger';
            $title='This card is past due.';
        }elseif($this->end<=strtotime('tomorrow'))
        {
            $css_class='label-warning';
            $title='This card is due in less than twenty-four hours.';
        }else
        {
            $css_class='label-default';
            $title='This card is due later.';
        }

        if($action=='index')
        {// برای صفحه اصلی
            if($this->start)
            {
                return '<label class="badge label-due-date badge-success" title="'.Yii::t("app","This card is complete.").'"><i class="fa fa-clock-o" ></i> '. Yii::$app->jdate->date("m/d",$this->end).'</label>';
            }else{
                return '<label class="badge badge-due-date '.$css_class.'" title="'.$title.'"><i class="fa fa-clock-o" ></i> '. Yii::$app->jdate->date("m/d",$this->end).'</label>';
            }

        }else
        {// برای صفحه جزئیات
            if($this->start)
            {// تسک کامل شده باشد
                return '<a href="#" class="label-due-date" data-ajax-url="'.Url::to(['task/due-date-complate', 'id'=>$this->id]).'"  onclick="return complateDueDate(this);"><span class="label  label-success" title="'.Yii::t("app","This card is complete.").'"><i class="fa fa-check-square-o" ></i> '. Yii::$app->jdate->date("Y/m/d ساعت H:i",$this->end).'</span></a>';
            }else
            {
                return '<a href="#" class="label-due-date" data-ajax-url="'.Url::to(['task/due-date-complate', 'id'=>$this->id]).'"  onclick="return complateDueDate(this);"><span class="label  '.$css_class.'" title="'.$title.'"><i class="fa fa-square-o" ></i> '. Yii::$app->jdate->date("Y/m/d ساعت H:i",$this->end).'</span></a>';
            }
        }

    }

    /*
     * نمایش چک لیست و تعداد ایتم های انجام شده
     */
    public function getCheckListStatus()
    {
        $check_list=$this->getCheckLists()->active()->limit(1)->one();
        if($check_list)
        {
            $total_item=$check_list->getCheckListItems()->count();
            $done_item=$check_list->getCheckListItems()->done()->count();

            if($total_item==$done_item)
            {
                return '<span title="'.Yii::t("app","Checklist Items").'"> <label class="badge label-due-date badge-success"><i class="fa fa-check-square" ></i> '. $done_item.'/'.$total_item.'</label></span>';
            }else{
                return '<span  title="'.Yii::t("app","Checklist Items").'"><label class="badge label-due-date badge-secondary"><i class="fa fa-check-square" ></i> '. $done_item.'/'.$total_item.'</label></span>';
            }
        }
    }

    /*
     * نمایش ناتیفیکیشن های
     */
    public function getNotificationStatus()
    {
        $count_not=$this->getNotifications()->notWatch()->my(Yii::$app->user->id)->count();
        if($count_not)
        {
            return '<span title="'.Yii::t("app","Unread notifications").'"> <label class="badge label-due-date badge-danger"><i class="fa fa-bell-o" ></i> '. $count_not.'</label></span>';
        }
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->created = time();
            $this->creator_id = Yii::$app->user->id;
            $this->status= self::STATUS_ACTIVE;
            $this->t_order=$this->getCountItem($this->list_id)+1;
        }
        $this->update_id = Yii::$app->user->id;
        $this->changed = time();
        return parent::beforeSave($insert);
    }
}
