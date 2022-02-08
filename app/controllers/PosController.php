<?php

namespace app\controllers;

use app\controllers\base\BaseTransactionController;
use app\enums\Type_Model;
use app\enums\Type_Permission;
use app\enums\Status_Transaction;
use app\models\Item;
use app\models\ItemWarehouse;
use app\models\PosProfileForm;
use app\models\PosReceipt;
use app\models\PosReceiptItem;
use app\models\PosReceiptPayment;
use app\models\SalesInvoice;
use app\models\Setup;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;

class PosController extends BaseTransactionController
{
    public function init()
    {
        $this->modelClass = PosReceipt::class;
        $this->modelSearchClass = SalesInvoiceSearch::class;
        $this->itemModelClass = PosReceiptItem::class;
        $this->paymentModelClass = PosReceiptPayment::class;

        return parent::init();
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'on-hold-sale', 'cancel-sale'],
                'rules' => [
                    [
                        'actions' => ['index', 'on-hold-sale', 'cancel-sale'],
                        'allow' => true,
                        'roles' => [ Type_Permission::Create .' '. Type_Model::SalesInvoice ],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'on-hold-sale' => ['POST'],
                    'cancel-sale' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex($id = null)
    {
        if ($id) {
            $this->model = PosReceipt::findOne($id);
            $this->detailModels = $this->model->links;
        }
        else {
            $this->model = new PosReceipt();
            $this->detailModels = [
                'pos_receipt_item' => [],
                'pos_receipt_payment' => [],
            ];
        }

        $this->sidebar = false;

        // 1a. autosuggest id value if applicable
        if ( $this->model->autoSuggestIdValue() )
            $this->model->{$this->model->autoSuggestAttribute()} = $this->model->autoSuggestId();
    
        // 1b. save if request is via post
        if ( Yii::$app->request->isPost )
            return $this->saveModel();

        // 2. render the index page
        return $this->loadView();
    }

    // Default Sale Type (check POS profile)
    public function actionDefaultSaleType()
    {
        if (Yii::$app->request->isAjax)
        {
            $profile = Setup::getSettings(PosProfileForm::class);
            $labels = PosReceipt::getSaleLabelsBy($profile->default_sale_type);
            $values = PosReceipt::getSaleValuesBy($profile->default_sale_type);

            return $this->asJson(array_merge($labels, $values));
        }
        // else
        Yii::$app->end();
    }

    // Sale Type (check user selection)
    public function actionChangeSaleType()
    {
        if (Yii::$app->request->isAjax)
        {
            $saleType = Yii::$app->request->get('saleType');
            $labels = PosReceipt::getSaleLabelsBy($saleType);
            $values = PosReceipt::getSaleValuesBy($saleType);

            return $this->asJson(array_merge($labels, $values));
        }
        // else
        Yii::$app->end();
    }

    // On Hold Sale (query SalesInvoice with Draft status)
    public function actionOnHoldSale()
    {
        if (Yii::$app->request->isAjax)
        {
            $sales = SalesInvoice::find()->where(['status' => Status_Transaction::Draft])->all();

            return $this->renderAjax('cart/_sale_on_hold', [
                        'sales' => $sales
                    ]);
        }
        // else
        Yii::$app->end();
    }

    // Cancel Sale (clear POST and refresh the UI)
    public function actionCancelSale($id = null)
    {
        if ($id)
            SalesInvoice::findOne($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionItemSearch()
    {
        if (Yii::$app->request->isAjax)
        {
            // $pos_receipt = new PosReceipt();
            // $itemGroups = $pos_receipt->posProfile->itemGroups;
            $items = (new \yii\db\Query())
                        ->select(['i.id', 'i.name'])
                        ->from('item_warehouse iw')
                        ->join('RIGHT JOIN', 'item i', 'i.id = iw.item_id')
                        ->where([
                            'i.inactive' => false,
                            'i.is_sales_item' => true,
                            'iw.warehouse_id' => Yii::$app->request->get('itemWarehouse')
                        ])
                        //   ->andWhere(['in', 'i.item_group', $itemGroups])
                          ->andWhere(['like', 'i.barcode', Yii::$app->request->get('itemSearch')])
                        //   ->limit(100)
                          ->all();
            if (empty($items))
                $items = (new \yii\db\Query())
                            ->select(['i.id', 'i.name'])
                            ->from('item_warehouse iw')
                            ->join('RIGHT JOIN', 'item i', 'i.id = iw.item_id')
                            ->where([
                                'i.inactive' => false,
                                'i.is_sales_item' => true,
                                'iw.warehouse_id' => Yii::$app->request->get('itemWarehouse')
                            ])
                            ->andWhere(['like', 'item_id', Yii::$app->request->get('itemSearch')])
                            // ->limit(100)
                            ->all();
            if (empty($items))
                $items = (new \yii\db\Query())
                            ->select(['i.id', 'i.name'])
                            ->from('item_warehouse iw')
                            ->join('RIGHT JOIN', 'item i', 'i.id = iw.item_id')
                            ->where([
                                'i.inactive' => false,
                                'i.is_sales_item' => true,
                                'iw.warehouse_id' => Yii::$app->request->get('itemWarehouse')
                            ])
                            ->andWhere(['like', 'i.name', Yii::$app->request->get('itemSearch')])
                            // ->limit(100)
                            ->all();
            $results = [];
            foreach ($items as $item) {
                $results[] =
                    Html::a(
                        Html::tag('div', $item['id'], ['class' => 'right floated content']) .
                            Html::tag('div', $item['name'], ['class' => 'content']),
                        Url::to(['pos/add-cart-item', 'id' => $item['id']]),
                        [
                            'class' => 'item',
                            'id' => $item['id'],
                        ]);
            }

            return $this->asJson(['result' => $results]);
		}
    }

    public function actionItemGroupFilter()
    {
        if (Yii::$app->request->isAjax)
        {
            $items = Item::find()
                        ->where(['inactive' => false, 'is_sales_item' => true])
                        // ->andWhere(['item_group' => Yii::$app->request->get('group_id')])
                        // ->asArray()
                        ->all();
            $selectOptionTags = '';
            foreach ($items as $item) {
                $selectOptionTags .= Html::tag('option', $item->name, ['value' => $item->id]);
            }
            return $selectOptionTags;
        }
        // else
        Yii::$app->end();
    }

    public function actionAddNewCustomer()
    {
        if (Yii::$app->request->isAjax)
        {
            return $this->renderAjax('/cart/_add_customer');
        }
        // else
        Yii::$app->end();
    }

    public function actionAddCartItem()
    {
        if ( Yii::$app->request->isAjax )
        {
            $stock_item = Item::findOne(Yii::$app->request->post('itemId'));

            $pos_item = new PosReceiptItem();
            $pos_item->item_id = $stock_item->id;
            $pos_item->warehouse_id = Yii::$app->request->post('warehouseId');
            $pos_item->item_name = $stock_item->name;
            $pos_item->unit_price = $stock_item->standard_rate;
            $pos_item->quantity = 1; // default value
            $pos_item->tax_percent = $stock_item->tax_rate;
            $pos_item->total_amount = $pos_item->unit_price * $pos_item->quantity;

            $pos_receipt = new PosReceipt();

            $image = $this->renderAjax('cart/item/_image', [
                'stock_item' => $stock_item,
                'pos_receipt_item' => $pos_item,
                'pos_profile' => $pos_receipt->posProfile,
                'rowId' => Yii::$app->request->post('nextRowId')
            ]);

            $item = $this->renderAjax('cart/item/_form', [
                'stock_item' => $stock_item,
                'pos_receipt_item' => $pos_item,
                'pos_profile' => $pos_receipt->posProfile,
                'rowId' => Yii::$app->request->post('nextRowId')
            ]);

            return $this->asJson(['item' => $item, 'image' => $image]);
        }
        // else
        Yii::$app->end();
    }

    public function actionDeleteCartItem()
    {
        if ( Yii::$app->request->isAjax )
        {
            return $this->renderAjax('cart/item/_no_item');
        }
        // else
        Yii::$app->end();
    }
}
