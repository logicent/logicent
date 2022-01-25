<?php

namespace app\models;

use app\models\base\BaseTransactionItemSearch;

class PurchaseInvoiceItemSearch extends BaseTransactionItemSearch
{
    public function init()
    {
        $this->documentModelClass = PurchaseInvoiceItem::class;
        $this->documentIdAttribute = 'purchase_invoice_id';
    }
}
