<?php

namespace hesabro\trello\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "tbl_check_list_item".
 *
 * @property int $id
 * @property string $creator_id
 * @property string $update_id
 * @property int $check_list_id
 * @property string $title_item
 * @property int $status
 * @property string $created
 * @property string $changed
 *
 * @property User $creator
 * @property User $update
 * @property TblCheckList $checkList
 */
class CheckListItem extends \yii\db\ActiveRecord
{
    const STATUS_DONE = 1;
    const STATUS_NEW = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_check_list_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['check_list_id', 'title_item'], 'required'],
            [['creator_id', 'update_id', 'check_list_id', 'status', 'created', 'changed'], 'integer'],
            [['title_item'], 'string', 'max' => 48],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['creator_id' => 'id']],
            [['update_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['update_id' => 'id']],
            [['check_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => CheckList::class, 'targetAttribute' => ['check_list_id' => 'id']],
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
            'check_list_id' => Yii::t('app', 'Check List ID'),
            'title_item' => Yii::t('app', 'Title Item'),
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
    public function getCheckList()
    {
        return $this->hasOne(TblCheckList::class, ['id' => 'check_list_id']);
    }

    /**
     * @inheritdoc
     * @return CheckListItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CheckListItemQuery(get_called_class());
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->created = time();
            $this->creator_id = Yii::$app->user->id;
            $this->status= self::STATUS_NEW;
        }
        $this->update_id = Yii::$app->user->id;
        $this->changed = time();
        return parent::beforeSave($insert);
    }
}
