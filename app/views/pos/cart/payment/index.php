<?php

use app\enums\Payment_Method;
use app\helpers\SelectableItems;
use app\models\PaymentMethod;
use app\models\PosReceiptPayment;


?>

<table id="payments" class="ui very basic table">
	<!-- <thead>
		<tr>
			<th class="ui small centered sub header text-muted" colspan="2"><?= Yii::t('app', 'Add Payment') ?></th>
		</tr>
	</thead> -->
	<!-- Payment methods -->
	<tbody id="pos__payment_detail">
<?php
	$pos_receipt_payment = new PosReceiptPayment();
	$paymentMethods = SelectableItems::get(PaymentMethod::class, $pos_receipt_payment, [
		'valueAttribute' => 'id',
		'filters' => ['inactive' => false],
		'addEmptyFirstItem' => false,
	]);
	$rowId = 0;
	foreach ($paymentMethods as $paymentMethod) :
		echo $this->render('_form', [
				'pos_receipt_payment' => $pos_receipt_payment,
				'paymentMethod' => $paymentMethod,
				'rowId' => $rowId += 1
			]);
	endforeach ?>
	</tbody>
</table>

<?php $this->registerJs($this->render('index.js')) ?>
