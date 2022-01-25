<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Supplier;

/**
 * SupplierSearch represents the model behind the search form about `app\models\Supplier`.
 */
class SupplierSearch extends Supplier
{
    public function rules()
    {
        return [
            [[
                'id', 'inactive', 'default_currency', 'default_price_list', 'credit_days', 'credit_days_based_on'
            ], 'integer'],
            [[
                'updated_by', 'created_by', 'phone_number', 'name', 'comments', 'tags', 'supplier_details',
                'tax_Id', 'gender', 'supplier_type', 'created_at', 'updated_at'
            ], 'safe'],
            [['credit_limit'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Supplier::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if ($this->inactive == '-1')
            $this->inactive = null;

        if ($this->supplier_type == '-1')
            $this->supplier_type = null;
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'inactive' => $this->inactive,
            'default_currency' => $this->default_currency,
            'default_price_list' => $this->default_price_list,
            'credit_days' => $this->credit_days,
            'credit_days_based_on' => $this->credit_days_based_on,
            'credit_limit' => $this->credit_limit,
            // 'is_frozen' => $this->is_frozen,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'supplier_details', $this->supplier_details])
            ->andFilterWhere(['like', 'tax_Id', $this->tax_Id])
            ->andFilterWhere(['like', 'supplier_type', $this->supplier_type]);

        return $dataProvider;
    }
}
