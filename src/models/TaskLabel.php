<?php

namespace hesabro\trello\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "tbl_task_label".
 *
 * @property string $id
 * @property string $creator_id
 * @property string $update_id
 * @property string $label_id
 * @property string $task_id
 * @property string $status
 * @property string $created
 * @property string $changed
 *
 * @property User $creator
 * @property Label $label
 * @property ProjectTask $task
 * @property User $update
 */
class TaskLabel extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_task_label';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label_id', 'task_id'], 'required'],
            [['creator_id', 'update_id', 'label_id', 'task_id', 'status', 'created', 'changed'], 'integer'],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['creator_id' => 'id']],
            [['label_id'], 'exist', 'skipOnError' => true, 'targetClass' => Label::class, 'targetAttribute' => ['label_id' => 'id']],
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
            'label_id' => Yii::t('app', 'Label ID'),
            'task_id' => Yii::t('app', 'Task ID'),
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
    public function getLabel()
    {
        return $this->hasOne(Label::class, ['id' => 'label_id']);
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
     * @return TaskLabelQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TaskLabelQuery(get_called_class());
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

    public static function itemAlias($type,$code=NULL) {

        $_items = [
            'Color'=>[
                'green'=>'card-label-green',
                'yellow'=>'card-label-yellow',
                'orange'=>'card-label-orange',
                'red'=>'card-label-red',
                'purple'=>'card-label-purple',
                'blue'=>'card-label-blue',
                'sky'=>'card-label-sky',
                'lime'=>'card-label-lime',
                'pink'=>'card-label-pink',
                'black'=>'card-label-black',
            ]

        ];
        if (isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
        else
            return isset($_items[$type]) ? $_items[$type] : false;
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
