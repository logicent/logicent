<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'Sales Tax Charge');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Accounts'), 'url' => ['/accounts']];

$columns = [
    'rate',
];

$controller = $this->context->id;

echo $this->render('//_list/GridView', [
        'dataProvider' => $dataProvider, 
        'searchModel' => $searchModel,
        'columns' => $columns
    ]);