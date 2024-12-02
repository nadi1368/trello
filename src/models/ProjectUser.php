<?php

namespace hesabro\trello\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "tbl_project_user".
 *
 * @property string $id
 * @property string $creator_id
 * @property string $update_id
 * @property string $user_id
 * @property string $project_id
 * @property string $role
 * @property string $is_creator
 * @property string $status
 * @property string $created
 * @property string $changed
 *
 * @property User $creator
 * @property Project $project
 * @property User $update
 * @property User $user
 */
class ProjectUser extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;

    const ROLE_ADMIN = 1;
    const ROLE_USER= 2;

    const YES=1;
    const NO=0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_project_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'project_id'], 'required'],
            [['creator_id', 'update_id', 'user_id', 'project_id', 'status', 'created', 'changed', 'role', 'is_creator'], 'integer'],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['creator_id' => 'id']],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::class, 'targetAttribute' => ['project_id' => 'id']],
            [['update_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['update_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'user_id' => Yii::t('app', 'User ID'),
            'project_id' => Yii::t('app', 'Project ID'),
            'status' => Yii::t('app', 'Status'),
            'role' => Yii::t('app', 'Role'),
            'is_creator' => Yii::t('app', 'Is Creator'),// کاربری که این پروژه را ایجاد کرده است به عنوان ادمین اصلی این پروژه
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
    public function getProject()
    {
        return $this->hasOne(Project::class, ['id' => 'project_id']);
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
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return ProjectUserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProjectUserQuery(get_called_class());
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
     * کاربری که پروژه ساخته است
     * // ذخیره مشخصات این کاربر به عنوان کاربر این پروزه با سطح دسترسی مدیر
     */
    public static function saveAfterCreateProject($project_id, $user_id)
    {
        $model=new self();
        $model->project_id=$project_id;
        $model->user_id=$user_id;
        $model->is_creator=self::YES;
        $model->role=self::ROLE_ADMIN;
        if($model->save())
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
