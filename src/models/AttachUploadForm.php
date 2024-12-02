<?php
namespace hesabro\trello\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class AttachUploadForm extends Model
{
    /**
     * @var UploadedFile
     */

    public $file_name;

    public function rules()
    {
        return [
            [['file_name'], 'file', 'skipOnEmpty' => false, 'mimeTypes' => ['image/jpeg', 'image/jpeg', 'image/png', 'application/msword', 'application/excel', 'application/x-excel', 'application/x-compressed', 'application/x-zip-compressed', 'application/zip', 'multipart/x-zip', 'application/x-rar-compressed', 'application/pdf'], 'maxSize' => 1024 * 1024 * 3],
        ];
    }

    public function attributeLabels()
    {
        return [
            'file_name'=>  Yii::t('app', 'File'),

        ];
    }

}

