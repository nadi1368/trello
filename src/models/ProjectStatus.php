<?php

namespace hesabro\trello\models;

use Yii;
use common\models\User;
use Exception;
use yii\db\Expression;

/**
 * This is the model class for table "tbl_project_status".
 *
 * @property int $id
 * @property string $creator_id
 * @property string $update_id
 * @property string $project_id
 * @property string $title_status
 * @property int $s_order
 * @property int $status
 * @property string $created
 * @property string $changed
 *
 * @property User $creator
 * @property User $update
 * @property Project $project
 * @property ProjectTask[] $tblProjectTasks
 */
class ProjectStatus extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_project_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'title_status'], 'required'],
            [['creator_id', 'update_id', 'project_id', 's_order', 'status', 'created', 'changed'], 'integer'],
            [['title_status'], 'string', 'max' => 48],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['creator_id' => 'id']],
            [['update_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['update_id' => 'id']],
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
            'title_status' => Yii::t('app', 'Title Status'),
            's_order' => Yii::t('app', 'S Order'),
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
    public function getProject()
    {
        return $this->hasOne(Project::class, ['id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectTasks()
    {
        return $this->hasMany(ProjectTask::class, ['list_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return ProjectStatusQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProjectStatusQuery(get_called_class());
    }

    protected function getCountItem($p_id)
    {
       return self::find()->findByProject($p_id)->count();
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
     */

    public function move($new_pos)
    {
        $old_pos_real=$this->s_order;//self::find()->active()->andWhere('s_order<:ThisOrder',[':ThisOrder'=>$this->s_order])->count()+1;// موقعیت این لیست با توجه به لیست های فعال قبل از آن
        $new_pos_real=self::find()->findByProject($this->project_id)->active()->orderBy('s_order')->offset($new_pos-1)->one()->s_order;
        $this->s_order=$new_pos_real;
        $transaction = \Yii::$app->db->beginTransaction();
        try {
                if($flag = $this->save())
                {
                    if($new_pos_real<$old_pos_real)
                    {
                        $flag = self::updateAll(['s_order' => new Expression('s_order + (1)')],
                            'project_id=:Project AND s_order>=:NewPos AND s_order<:OldPos AND id<>:ThisId',[':Project'=>$this->project_id, ':NewPos'=>$new_pos_real, ':OldPos'=>$old_pos_real, ':ThisId'=>$this->id]);

                    }else
                    {
                        $flag = self::updateAll(['s_order' => new Expression('s_order - (1)')],
                            'project_id=:Project AND s_order<=:NewPos AND s_order>:OldPos AND id<>:ThisId',[':Project'=>$this->project_id, ':NewPos'=>$new_pos_real, ':OldPos'=>$old_pos_real, ':ThisId'=>$this->id]);

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
    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->created = time();
            $this->creator_id = Yii::$app->user->id;
            $this->status= self::STATUS_ACTIVE;
            $this->s_order=$this->getCountItem($this->project_id)+1;
        }
        $this->update_id = Yii::$app->user->id;
        $this->changed = time();
        return parent::beforeSave($insert);
    }

}
