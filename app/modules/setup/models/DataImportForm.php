<?php

namespace crudle\setup\models;

use crudle\main\enums\Type_Model;
use crudle\main\models\UploadForm;
use crudle\setup\enums\Type_Permission;
use Yii;
use yii\base\Model;

class DataImportForm extends Model
{
    public $dataFile;
    // public $dataTableName;
    public $addNewRecords = true;
    public $updateRecords = false;
    public $uploadForm;

    public function init()
    {
        $this->uploadForm = new UploadForm();
    }

    public function rules()
    {
        return [
            [['dataFile'], 'file'],
            [['dataFile'], 'file', 'extensions' => ['csv']],
            // [['targetTableName'], 'required'],
            [['addNewRecords', 'updateRecords'], 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'dataFile' => Yii::t('app', 'Data file'),
            'addNewRecords' => Yii::t('app', 'Add new records'),
            'updateRecords' => Yii::t('app', 'Update existing records'),
        ];
    }

    public function getListOptions()
    {
        return array_flip(Type_Model::modelClasses());
    }

    public static function permissions()
    {
        return [Type_Permission::List => Type_Permission::List];
    }
}
