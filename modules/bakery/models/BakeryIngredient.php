<?php

namespace logicent\bakery\models;

use crudle\main\models\base\BaseActiveRecord;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

class BakeryIngredient extends BaseActiveRecord
{
    public static function tableName()
    {
        return 'bakery_ingredient';
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::class,
            ],
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                    // ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function ($event) {
                    return new \yii\db\Expression('NOW()');
                },
            ]
        ];
    }

    public function rules()
    {
        return [
            [['baked_good_id', 'ingredient_id'], 'required'],
            [['baked_good_id', 'ingredient_id'], 'integer'],
            [['qty_required', 'unit_cost', 'total_cost'], 'number'],
            [['description', 'consumed_in'], 'string', 'max' => 140],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'baked_good_id' => Yii::t('app', 'Baked Good'),
            'ingredient_id' => Yii::t('app', 'Ingredient'),
            'description' => Yii::t('app', 'Description'),
            'consumed_in' => Yii::t('app', 'Consumed In'),
            'qty_required' => Yii::t('app', 'Qty Required'),
            'unit_cost' => Yii::t('app', 'Unit Cost'),
            'total_cost' => Yii::t('app', 'Total Cost'),
        ];
    }

    public function getIngredient()
    {
        return $this->hasOne(StockItem::class, ['id' => 'ingredient_id']);
    }

    public function getStockItem()
    {
        return $this->hasOne(StockItem::class, ['id' => 'baked_good_id']);
    }
}
