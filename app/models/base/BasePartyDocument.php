<?php

namespace app\models\base;

use app\enums\Status_Party;
use app\models\base\BaseActiveRecord;
use app\models\setup\ListViewSettingsForm;
use Yii;
use yii\helpers\ArrayHelper;

abstract class BasePartyDocument extends BaseActiveRecord implements AutoincrementIdInterface
{
    public $status;

    public function init()
    {
        // parent::init();
        $this->listSettings = new ListViewSettingsForm();
        $this->listSettings->listNameAttribute = 'name'; // override in view index
        $this->listSettings->listIdAttribute = 'phone_number'; // override in view index
    }

    public function rules()
    {
        $rules = parent::rules();

        return ArrayHelper::merge([
            [['phone_number', 'name'], 'required'],
            [['email_address'], 'email'],
            [['credit_days', 'credit_limit'], 'number'],
            ['inactive', 'boolean'],
            [['default_currency', 'salutation'], 'string', 'max' => 5],
            [[
                'email_address', 'phone_number', 'default_price_list', 'credit_days_based_on', 'name', 'tax_Id'
            ], 'string', 'max' => 140],
        ], $rules);
    }

    public function attributeLabels()
    {
        return [
            'name'  => Yii::t('app', 'Name'),
            // 'salutation'    => Yii::t('app', 'Salutation'),
            'phone_number'     => Yii::t('app', 'Phone number'),
            'email_address'     => Yii::t('app', 'Email address'),
            'inactive'  => Yii::t('app', 'Inactive'),
            'default_currency'  => Yii::t('app', 'Default currency'),
            'default_price_list'    => Yii::t('app', 'Default price list'),
            'credit_days'   => Yii::t('app', 'Credit days'),
            'credit_days_based_on'  => Yii::t('app', 'Credit days based on'),
            'credit_limit'  => Yii::t('app', 'Credit limit'),
            'tax_Id'    => Yii::t('app', 'Tax ID'),
            // 'is_frozen'     => Yii::t('app', 'Is frozen'),
        ];
    }

    public static function enums()
    {
        return [
            'status' => Status_Party::class
        ];
    }

    // public function lockUpdate()
    // {
    // }

    public function lockDelete()
    {
        // if has existing transactions
    }

    public function lockPayment()
    {
        // 
    }

    public function docNumPrefix()
    {
    }

    public function docNumPreset()
    {
        return $this->docNumPrefix() . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert))
            return false;
        // else
        if ($this->isNewRecord)
            $this->id = $this->docNumPreset();

        return true;
    }

    public function afterFind()
    {
        $this->status = $this->inactive;

        return parent::afterFind();
    }
}
