<?php

namespace hesabro\trello\models;

use Yii;
use common\models\User;
use Exception;

/**
 * This is the model class for table "tbl_label".
 *
 * @property string $id
 * @property string $creator_id
 * @property string $update_id
 * @property string $label_name
 * @property string $color_code
 * @property string $status
 * @property string $created
 * @property string $changed
 *
 * @property User $creator
 * @property User $update
 * @property TaskLabel[] $tblTaskLabels
 */
class Label extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_label';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label_name', 'color_code'], 'required'],
            [['creator_id', 'update_id', 'status', 'created', 'changed'], 'integer'],
            [['label_name'], 'string', 'max' => 255],
            [['color_code'], 'string', 'max' => 10],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['creator_id' => 'id']],
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
            'label_name' => Yii::t('app', 'Label Name'),
            'color_code' => Yii::t('app', 'Color Code'),
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
    public function getTaskLabels()
    {
        return $this->hasMany(TaskLabel::class, ['label_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return LabelQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LabelQuery(get_called_class());
    }
    /*
      * حذف منطقی
      */
    public function softDelete()
    {
        $this->status= self::STATUS_DELETED;
        $task_labels=$this->getTaskLabels()->active()->all();
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if($flag=$this->save(false))
            {
                foreach($task_labels as $task_label)
                {
                    if(!$flag=$task_label->softDelete())
                    {
                        break;
                    }
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
