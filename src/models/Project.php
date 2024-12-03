<?php

namespace hesabro\trello\models;

use Yii;
use common\models\User;
use hesabro\trello\Module;

/**
 * This is the model class for table "tbl_project".
 *
 * @property string $id
 * @property string $creator_id
 * @property string $update_id
 * @property string $user_id
 * @property string $project_name
 * @property string $project_status
 * @property string $status
 * @property string $public_or_private
 * @property string $color
 * @property string $created
 * @property string $changed
 *
 * @property ProjectFallow[] $projectFallows
 * @property ProjectFiles[] $projectFiles
 * @property ProjectLog[] $projectLogs
 * @property Notifications[] $tblNotifications
 * @property User $creator
 * @property User $update
 * @property User $user
 * @property ProjectStatus[] $tblProjectStatuses
 * @property ProjectTeams[] $tblProjectTeams
 * @property ProjectUser[] $tblProjectUsers
 * @property TaskAssignment[] $tblTaskAssignments
 */
class Project extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;

    const SHOW_PRIVATE=1;
    const SHOW_PUBLIC=2;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_project';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_name', 'public_or_private'], 'required'],
            [['creator_id', 'update_id', 'user_id', 'project_status', 'status', 'created', 'changed', 'public_or_private'], 'integer'],
            [['project_name'], 'string', 'max' => 255],
            [['color'], 'string', 'max' => 32],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['creator_id' => 'id']],
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
            'project_name' => Yii::t('app', 'Project Name'),
            'project_status' => Yii::t('app', 'Project Status'),
            'status' => Yii::t('app', 'Status'),
            'public_or_private'=>Yii::t("app","Public Or Private"),
            'color'=>Yii::t("app","Color"),
            'created' => Yii::t('app', 'Created'),
            'changed' => Yii::t('app', 'Changed'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectFallows()
    {
        return $this->hasMany(ProjectFallow::class, ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectFiles()
    {
        return $this->hasMany(ProjectFiles::class, ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectLogs()
    {
        return $this->hasMany(ProjectLog::class, ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotifications()
    {
        return $this->hasMany(Notifications::class, ['project_id' => 'id']);
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
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectStatuses()
    {
        return $this->hasMany(ProjectStatus::class, ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectTeams()
    {
        return $this->hasMany(ProjectTeams::class, ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectUsers()
    {
        return $this->hasMany(ProjectUser::class, ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskAssignments()
    {
        return $this->hasMany(TaskAssignment::class, ['project_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return ProjectQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProjectQuery(get_called_class());
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
            'ShowStatus'=>[
                self::SHOW_PRIVATE=>Module::t("module", "Private Board"),
                self::SHOW_PUBLIC=>Module::t("module", "Public Board")
            ],
        ];
        
        if (isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
        else
            return isset($_items[$type]) ? $_items[$type] : false;
    }

    /*
     * آیا کاربر اجازه دسترسی به این پروژه را دارد
     */
    public function access($user_id)
    {
        $team_user=TeamUsers::find()
            ->select('project_id')
            ->joinWith([
                'team'=>function($query){
                    return $query->active();
                }
            ])
            ->leftJoin('tbl_project_teams', '`tbl_project_teams`.`team_id` = `tbl_team_users`.`team_id` AND project_id=:Project',[':Project'=>$this->id])
            ->findByUser($user_id)
            ->active()
            ->count(); //آیا تیم هایی که این کاربر دارد به این پروژه دسترسی دارد

        $project_user=$this->getProjectUsers()->findByUser($user_id)->active()->count();// آیا کاربر در لیست کاربران این پروژه است


        if($team_user>0 || $project_user >0 || $this->public_or_private==self::SHOW_PUBLIC) // و یا پروژه عمومی است
        {
            return true;
        }else
        {
            return false;
        }
    }

    /*
     * آیا کاربر این پروژه دسترسی ادمین دارد
     */
    public function isAdmin($user_id)
    {
        $project_user=$this->getProjectUsers()->findByUser($user_id)->findByAdmin()->active()->count();
        if($project_user>0)
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
