<?php

namespace hesabro\trello\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "tbl_check_list".
 *
 * @property int $id
 * @property string $creator_id
 * @property string $update_id
 * @property string $task_id
 * @property string $title_ch
 * @property int $status
 * @property string $created
 * @property string $changed
 *
 * @property User $creator
 * @property User $update
 * @property ProjectTask $task
 */
class CheckList extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_check_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'title_ch'], 'required'],
            [['creator_id', 'update_id', 'task_id', 'status', 'created', 'changed'], 'integer'],
            [['title_ch'], 'string', 'max' => 48],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['creator_id' => 'id']],
            [['update_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['update_id' => 'id']],
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
            'update_id' => Yii::t('app', 'Update ID'),
            'task_id' => Yii::t('app', 'Task ID'),
            'title_ch' => Yii::t('app', 'Title Ch'),
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
    public function getCheckListItems()
    {
        return $this->hasMany(CheckListItem::class, ['check_list_id' => 'id']);
    }
    /**
     * @inheritdoc
     * @return CheckListQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CheckListQuery(get_called_class());
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
     * درصد انجام شدن ایتم ها
     */
    public function getPercent()
    {
        $total_item=$this->getCheckListItems()->count();
        $done_item=$this->getCheckListItems()->done()->count();

        return $total_item ? intval($done_item/$total_item*100) : 0;
    }
    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->created = time();
            $this->creator_id = Yii::$app->user->id;
            $this->status= self::STATUS_ACTIVE;
        }
        $this->update_id = Yii::$app->user->id;
        $this->changed = time();
        return parent::beforeSave($insert);
    }
}
