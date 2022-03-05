<?php

use yii\helpers\Html;

$columns = [
    'salary_structure',
    'employee_id',
    'employee_name',
    'designation',
    'company',
    //'branch',
    //'department',
    //'from_period',
    //'to_period',
    //'working_days',
    //'hour_rate',
    //'leave_without_pay',
    //'payment_days',
    //'gross_pay',
    //'total_deduction',
    //'total_principal_amount',
    //'total_loan_repayment',
    //'total_interest_amount',
    //'net_pay',
    //'rounded_total',
    //'total_working_hours',
    //'bank_name',
    //'bank_account_no',
    //'has_timesheet:datetime',
];

echo $this->render('/layouts/_view/_gridView', [
                            'dataProvider' => $dataProvider, 
                            'searchModel' => $searchModel,
                            'columns' => $columns
                        ]);