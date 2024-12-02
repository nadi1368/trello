<?php

namespace hesabro\trello\models;

use Yii;
use common\models\User;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_team".
 *
 * @property int $id
 * @property string $creator_id
 * @property string $update_id
 * @property string $title_team
 * @property int $status
 * @property string $created
 * @property string $changed
 *
 * @property User $creator
 * @property User $update
 * @property TeamUsers[] $tblTeamUsers
 */
class Team extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_team';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title_team'], 'required'],
            [['creator_id', 'update_id', 'status', 'created', 'changed'], 'integer'],
            [['title_team'], 'string', 'max' => 48],
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
            'title_team' => Yii::t('app', 'Title Team'),
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
    public function getTeamUsers()
    {
        return $this->hasMany(TeamUsers::class, ['team_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return TeamQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TeamQuery(get_called_class());
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
        $list=[];
        if($type=='List')
        {
            //لیست تیم هایی که این کاربر در آنها نقش ادمین دارد
            $data_list=TeamUsers::find()
                ->joinWith(['team'=>function($query) { return $query->active(); }])
                ->findByAdmin()
                ->findByUser(Yii::$app->user->id)
                ->active()
                ->all();
            $list=ArrayHelper::map($data_list,'team.id', 'team.title_team');
        }

        $_items = [
            'List'=>$list,
        ];
        if (isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
        else
            return isset($_items[$type]) ? $_items[$type] : false;
    }

    /*
    * آیا کاربر این پروژه دسترسی ادمین دارد
    */
    public function isAdmin($user_id)
    {
        $project_user=$this->getTeamUsers()->findByUser($user_id)->findByAdmin()->active()->count();
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
