<?php

namespace logicent\hr\models;

use crudle\main\models\base\BaseActiveRecord;
use Yii;

class PayrollEntry extends BaseActiveRecord
{
    public static function tableName()
    {
        return 'payroll_entry';
    }

    public function rules()
    {
        return [
            [['salary_structure'], 'integer'],
            [['posting_date', 'start_period', 'end_period'], 'required'],
            [['posting_date', 'start_period', 'end_period'], 'safe'],
            [['payroll_frequency'], 'string'],
            [['salary_structure'], 'exist', 'skipOnError' => true, 'targetClass' => SalaryStructure::class, 'targetAttribute' => ['salary_structure' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'salary_structure' => Yii::t('app', 'Salary Structure'),
            'posting_date' => Yii::t('app', 'Posting Date'),
            'payroll_frequency' => Yii::t('app', 'Payroll Frequency'),
            'start_period' => Yii::t('app', 'Start Period'),
            'end_period' => Yii::t('app', 'End Period'),
        ];
    }

    public function getSalaryStructure()
    {
        return $this->hasOne(SalaryStructure::class, ['id' => 'salary_structure']);
    }
}
