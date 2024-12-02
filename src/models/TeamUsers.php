<?php

namespace hesabro\trello\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "tbl_team_users".
 *
 * @property int $id
 * @property string $creator_id
 * @property string $update_id
 * @property int $team_id
 * @property string $user_id
 * @property int $status
 * @property string $created
 * @property string $changed
 *
 * @property User $creator
 * @property User $update
 * @property TblTeam $team
 * @property User $user
 */
class TeamUsers extends \yii\db\ActiveRecord
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
        return 'tbl_team_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['team_id', 'user_id', 'role'], 'required'],
            [['creator_id', 'update_id', 'team_id', 'role', 'is_creator', 'user_id', 'status', 'created', 'changed'], 'integer'],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['creator_id' => 'id']],
            [['update_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['update_id' => 'id']],
            [['team_id'], 'exist', 'skipOnError' => true, 'targetClass' => Team::class, 'targetAttribute' => ['team_id' => 'id']],
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
            'team_id' => Yii::t('app', 'Team ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'role' => Yii::t('app', 'Role'),
            'is_creator' => Yii::t('app', 'Is Creator'),// کاربری که این تیم را ایجاد کرده است به عنوان ادمین اصلی این تیم
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
    public function getTeam()
    {
        return $this->hasOne(Team::class, ['id' => 'team_id']);
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
     * @return TeamUsersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TeamUsersQuery(get_called_class());
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
            'Role'=>[
                self::ROLE_ADMIN =>Yii::t('app','Admin'),
                self::ROLE_USER=>Yii::t('app','User'),
            ],

        ];
        if (isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
        else
            return isset($_items[$type]) ? $_items[$type] : false;
    }

    /*
     * چک کردن اینکه کاربر به این تیم دسترسی دارد
     * is_admin
     * آیا نقش ادمین دارد
     */
    public static function access($team_id, $user_id, $is_admin=false)
    {
        $main_query=self::find()
            ->joinWith([
                'team'=>function($query) use($team_id){
                    return $query->active()->andWhere([Team::tableName().'.id'=>$team_id]);
                }
            ])
            ->findByUser($user_id)
            ->active();
        if($is_admin)
        {
            $main_query->findByAdmin();
        }

        if($main_query->count(self::tableName().'.`id`')>0)
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
