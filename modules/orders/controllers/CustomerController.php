<?php
/**
 * This file is part of Shopbay.org (http://shopbay.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.orders.components.GuestOrderControllerTrait');
Yii::import('common.modules.orders.models.*');
/**
 * Description of CustomerController
 *
 * @author kwlok
 */
class CustomerController extends SPageIndexController 
{   
    use GuestOrderControllerTrait, OrderControllerTrait;

    public function init()
    {
        parent::init();
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'Order';//to display recent orders at welcome page
        $this->viewName = Sii::t('sii','Orders');
        $this->route = 'orders/customer/index';
        $this->pageControl = SPageIndex::CONTROL_ARROW;
        $this->searchMap = $this->searchMapData['map'];
        //-----------------//  
        // SPageFilter Configuration
        // @see SPageFilterControllerTrait
        $this->filterFormModelClass = $this->searchMapData['class'];
        $this->filterFormHomeUrl = url('orders/customer');
        $this->filterFormQuickMenu = [
            ['id'=>'items','title'=>Sii::t('sii','View Items'),'subscript'=>Sii::t('sii','items'), 'url'=>url('items')],
            ['id'=>'po','title'=>Sii::t('sii','View Orders'),'subscript'=>Sii::t('sii','orders'), 'url'=>url('orders'),'linkOptions'=>['class'=>'active']],
        ];
        //-----------------
        // Exclude following actions from rights filter 
        //-----------------
        $this->rightsFilterActionsExclude = [
            'track',
        ];
    }
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array_merge(parent::actions(),[
            'view'=> [
                'class'=>'common.components.actions.ReadAction',
                'model'=>$this->modelType,
                'pageTitleAttribute'=>'order_no',
                'finderMethod'=>'orderNo',
            ],
            'track'=> [
                'class'=>'common.components.actions.ReadAction',
                'model'=>$this->modelType,
                'modelFilter'=>'guest',
                'pageTitleAttribute'=>'order_no',
                'finderMethod'=>'orderNo',
                'viewFile'=>'track',
            ],
        ]);
    } 
    
    protected function getSearchMapData()
    {
        $class = 'AllOrdersFilterForm';
        $map = [
            'order_no' => 'order_no',
            'date' => 'create_time',
            'shop' => 'shop_id',
            'items' => 'remarks',//attribute "remarks" is used as proxy to search into item names
            'price' => 'grand_total',
            'shipping' => 'item_shipping',
            'payment_method' => 'payment_method',
        ];
        if (user()->onShopScope()){
            $map = array_merge($map, [
                'shop' => 'shop_id',
            ]);
            $class = 'OrderFilterForm';
        }
        return ['map'=>$map,'class'=>$class];
    }
    /**
     * @inheritdoc
     */
    public function getDataProvider($scope,$searchModel=null)
    {
        $type = $this->modelType;
        $type::model()->resetScope();
        $finder = $type::model()->{$this->modelFilter}()->{$scope}();
        
        if (user()->onShopScope()){
            $finder = $finder->locateShop(user()->getShop());
        }
        
        if ($searchModel!=null)
            $finder->getDbCriteria()->mergeWith($searchModel->getDbCriteria());

        logTrace(__METHOD__.' criteria',$finder->getDbCriteria());
        
        return new CActiveDataProvider($finder, [
                'criteria'=>['order'=>$this->sortAttribute.' DESC'],
                'pagination'=>['pageSize'=>Config::getSystemSetting('record_per_page')],
                'sort'=>false,
            ]);
    }  
    
    protected function getSectionsData($model) 
    {
        Yii::import('common.modules.tasks.views.workflow.sections.OrderSections');
        $sections = new OrderSections($this,$model);
        $sections->addPaymentRecord(true, '_payment');
        $sections->addAttachments();
        $sections->addProcessHistory();
        return $sections->getData(false);//false = custom sections
    }    
    /**
     * OVERRIDE METHOD
     * @see SPageIndexController
     * @return CDbCriteria
     */
    public function getSearchCriteria($model)
    {
        $criteria=new CDbCriteria;
        $criteria->compare('order_no',$model->order_no,true);
        $criteria->compare('grand_total',$model->grand_total,true);
        $criteria = QueryHelper::prepareDatetimeCriteria($criteria, 'create_time', $model->create_time);
        $criteria = QueryHelper::parseJsonStringSearch($criteria, 'item_shipping', $model->item_shipping, '\"');
        $criteria = QueryHelper::parseJsonStringSearch($criteria, 'payment_method', $model->payment_method);
        //$criteria->compare('status', QueryHelper::parseStatusSearch($model->status));//already supported as scope filter in spageindex
        if (!empty($model->remarks))//attribute "remarks" is used as proxy to search into item names
            $criteria->addCondition($model->constructItemsInCondition($model->remarks));
        if (!empty($model->shop_id))
            $criteria->addCondition($model->constructShopsInCondition($model->shop_id));

        return $criteria;
    }
    /**
     * Return data provider of order shipping 
     * @param CActiveRecord $order
     * @return \CArrayDataProvider
     */
    protected function getOrderShippingDataProvider($order) 
    {
        return new CArrayDataProvider($order->getShippings(),['keyField'=>false,'sort'=>false,'pagination'=>false]);
    }    
    /**
     * Return data provider of order item s
     * @param CActiveRecord $order
     * @param integer $shipping_id
     * @return \CActiveDataProvider
     */
    protected function getOrderItemDataProvider($order,$shipping_id) 
    {
        Item::model()->resetScope();//required else second shipping onwards items won't get displayed
        $finder = Item::model()->order($order->id)->locateShop($order->shop->id)->shipping($shipping_id);
        return new CActiveDataProvider($finder, ['sort'=>false,'pagination'=>false]);
    } 
    /**
     * Return data provider of order item detailed information
     * @param CActiveRecord $item
     * @return \CArrayDataProvider
     */
    protected function getOrderItemInfoDataProvider($item) 
    {
        $domain = user()->onShopScope()?user()->shopModel->domain:null;
        $data = new CList();
        $data->add(array('id'=>'item_name','key'=>false,'value'=>Chtml::link($item->displayLanguageValue('name',user()->getLocale()), Item::getAccessUrl($item,$domain)).Helper::htmlColorText($item->getStatusText()),'cssClass'=>'info'));
        if ($item->isCampaignItem())
            $data->add(array('id'=>'item_campaign','key'=>false,'value'=>$item->getCampaignColorTag(user()->getLocale(),false),'cssClass'=>'info'));
        $data->add(array('id'=>'item_sku','key'=>Sii::t('sii','SKU'),'value'=>$item->product_sku,'cssClass'=>'info'));
        $options = $item->getOptions(user()->getLocale());
        if (!empty($options)){
            foreach($options as $key => $value){
                $data->add(array('id'=>$key,'key'=>$key,'value'=>$value,'cssClass'=>'info'));
            }
        }
        if ($item->weight!=null){
            $data->add(array('id'=>'item_weight','key'=>Sii::t('sii','Weight'),'value'=>$item->formatWeight($item->weight),'cssClass'=>'info'));
        }        
        if (isset($item->tracking_no)) {
            $data->add(array('id'=>'item_tracking','key'=>Sii::t('sii','Tracking No'),'value'=>'<span class="tracking-label">'.Chtml::link($item->tracking_no,$item->tracking_url,array('target'=>'_blank')).'</span>','cssClass'=>'info'));
        }
        if ($item->hasAffinity()){
            $data->add(array('id'=>'item_promotion_flag','key'=>false,'value'=>Helper::htmlColorTag(Sii::t('sii','Promotion'),'palevioletred',false),'cssClass'=>'info'));
        }
        return new CArrayDataProvider($data->toArray(),array('keyField'=>false,'sort'=>false,'pagination'=>false));
    }  
    /**
     * Return data provider of caorderrt item detailed pricing information
     * @param CActiveRecord $item
     * @return \CArrayDataProvider
     */
    protected function getOrderItemPriceInfoDataProvider($item,$locale) 
    {
        $data = new CList();
        $data->add(array('id'=>'price','key'=>false,'value'=>$item->formatCurrency($item->getPrice()),'cssClass'=>'info'));
        if ($item->getCampaignItem()!=false){
            $data->add(array('id'=>'usual_price','key'=>false,'value'=>$item->formatCurrency($item->unit_price),'cssClass'=>'info'));
            $data->add(array('id'=>'offer_tag','key'=>false,'value'=>Helper::htmlColorTag($item->getCampaignOfferTag($locale),'orange'),'cssClass'=>'info'));
        }
        if ($item->option_fee>0){
            $data->add(array('id'=>'option_fee','key'=>false,'value'=>Sii::t('sii','plus ').$item->formatCurrency($item->option_fee),'cssClass'=>'info'));
        }
        if ($item->shipping_surcharge>0){//item level shipping surcharge
            $data->add(array('id'=>'shipping_surcharge','key'=>false,'value'=>Sii::t('sii','plus ').$item->formatCurrency($item->shipping_surcharge),'cssClass'=>'info'));
        }
        return new CArrayDataProvider($data->toArray(),array('keyField'=>false,'sort'=>false,'pagination'=>false));
    }
    /**
     * Return data provider of shipping level subtotal 
     * @param CActiveRecord $order
     * @param integer $shipping
     * @return \CArrayDataProvider
     */
    protected function getOrderSubTotalDataProvider($order,$shipping) 
    {
        $dataArray = array(
            array('id'=>'items_subtotal_'.$shipping,'key'=>Sii::t('sii','Subtotal'),'value'=>$order->formatCurrency($order->getShippingItemSubtotal($shipping)),'cssClass'=>'total'),
            array('id'=>'shippingRate_subtotal_'.$shipping,'key'=>Sii::t('sii','Shipping Fee'),'value'=>$order->formatCurrency($order->getShippingRate($shipping)),'cssClass'=>'total'),
        );
        return new CArrayDataProvider($dataArray,array('keyField'=>false,'sort'=>false,'pagination'=>false));
    }     
    /**
     * Return data provider of shop level total 
     * @param CActiveRecord $order
     * @return \CArrayDataProvider
     */
    protected function getOrderTotalDataProvider($order) 
    {
        $dataArray = array(
            array('id'=>'items_total_'.$order->shop->id,'key'=>Sii::t('sii','Total Price'),'value'=>$order->formatCurrency($order->item_total),'cssClass'=>'total'),
        );
        
        //display sale campaign discount if any
        if ($order->hasCampaignSale()){
            $dataArray = array_merge($dataArray,array(
                array('id'=>'discount_total_'.$order->shop->id,'key'=>$this->stooltipWidget($order->getCampaignSaleText(user()->getLocale()),array('position'=>SToolTip::POSITION_LEFT),true).Sii::t('sii','Discount {offer_tag}',array('{offer_tag}'=>$order->getCampaignSaleOfferTag())),
                      'value'=>$order->getCampaignSaleDiscountText(),'cssClass'=>'total discount'),
            ));
        }
        
        //display promocode campaign discount if any
        if ($order->hasCampaignPromocode()){
            $dataArray = array_merge($dataArray,array(
                array('id'=>'promocode_total_'.$order->shop->id,'key'=>$this->stooltipWidget(CHtml::tag('span',array('class'=>'promocode-tooltip-content'),$order->getCampaignPromocodeTip()),array('position'=>SToolTip::POSITION_LEFT),true).CHtml::tag('span',array('class'=>'promocode-label'),$order->getCampaignPromocodeText(user()->getLocale())),
                      'value'=>$order->getCampaignPromocodeDiscountText(),'cssClass'=>'total promocode'),
            ));
        }
        
        //retreive tax payable breakdowns
        foreach ($order->getTaxDisplaySet(user()->getLocale()) as $taxName => $taxAmount) {
            $dataArray = array_merge($dataArray,array(
                array('id'=>'tax_total_'.$order->shop->id,'key'=>$taxName,'value'=>$order->shop->formatCurrency($taxAmount),'cssClass'=>'total tax'),
            ));
        }        
        
        $dataArray = array_merge($dataArray,array(
            array('id'=>'shippingFee_total_'.$order->shop->id,'key'=>Sii::t('sii','Total Shipping Fee'),'value'=>$order->formatCurrency($order->shipping_total),'cssClass'=>'total shipping'),
            //put a placeholder for free shipping discount column, if there is promocode data, put them in
            array('id'=>'shippingFee_discount_'.$order->shop->id,'key'=>$this->stooltipWidget(CHtml::tag('span',array('class'=>'promocode-tooltip-content'),$order->getDiscountFreeShippingTip()),array('position'=>SToolTip::POSITION_TOP),true).CHtml::tag('span',array('class'=>'promocode-label'),Sii::t('sii','Free Shipping')),
                  'value'=>$order->getDiscountFreeShippingDiscountText(),'cssClass'=>'total freeshipping'.($order->hasDiscountFreeShipping()?'':' hidden')),
            array('id'=>'grand_total_'.$order->shop->id,'key'=>Sii::t('sii','Grand Total'),'value'=>$order->formatCurrency($order->grand_total),'cssClass'=>'total grandtotal'),
        ));        
        
        //section: Order Refund 
        $refundAmount = $order->refundTotal;
        if ($refundAmount>0){
            $dataArray = array_merge($dataArray,array(
                array('id'=>'refund_total_'.$order->shop->id,'key'=>Sii::t('sii','Refund Total'),'value'=>$order->formatCurrency($refundAmount),'cssClass'=>'total refundtotal'),
            ));        
        }
      
        return new CArrayDataProvider($dataArray,array('keyField'=>false,'sort'=>false,'pagination'=>false));
    } 
    /**
     * Return order shipping remarks
     * @param type $order
     * @param type $shipping
     * @return type
     */
    protected function getOrderShippingRemarks($order,$shipping)
    {
        $remarks = new CList();
        if (is_array($order->getShippingRateText($shipping))){
            $remarks->add(Sii::t('sii','Shipping fee per order charged as below: {shipping_rate_text}',array('{shipping_rate_text}'=>Helper::htmlList($order->getShippingRateText($shipping)))));
            $remarks->add(Sii::t('sii','Above shipping fee excludes product shipping surcharge'));
        }
        else
            $remarks->add(Sii::t('sii','Shipping fee {shipping_rate_text} per order, excluding product shipping surcharge',array('{shipping_rate_text}'=>$order->getShippingRateText($shipping))));

        if ($order->getShippingSpeed($shipping)!=null)
            $remarks->add(Sii::t('sii','Estimated delivery within {duration} working days',array('{duration}'=>$order->getShippingSpeed($shipping))));
        
        return $remarks->toArray();
    } 
    
    protected function renderPaymentConfirmationSnippet($order)
    {
        Yii::import('common.extensions.braintree.widgets.HostedFieldsForm');
        $form = PaymentMethod::getFormInstance($order->getPaymentMethodMode());
        $paymentRecord = $order->getPayment();
        $params = array(
            'methodDesc'=>$order->getPaymentMethodName(user()->getLocale()),
            'trace_no'=>'',
            'note'=>isset($paymentRecord->note)?$paymentRecord->note:'',
        );  
        $extraParams = array();
        if ($paymentRecord!=null){
            $paymentTrace = $paymentRecord->getTraceNo(true);
            if (!empty($paymentTrace) && isset($paymentTrace['cardType'])){
                $extraParams = array(
                    'cardType' => $paymentTrace['cardType'],
                    'iconBaseUrl' => HostedFieldsForm::getCreditCardIconBaseUrl(),
                    'last4' => $paymentTrace['last4'],
                );
            }
        }
        return $form->renderConfirmationSnippet($params,empty($extraParams)?null:$extraParams);
    }
}