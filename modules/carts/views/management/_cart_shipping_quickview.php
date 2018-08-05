<?php 
$checkoutScript = 'offcanvascheckout("/cart/management/checkout?'.http_build_query($queryParams).'");';
$this->widget('common.widgets.SListView', [
        'dataProvider'=> $this->getCartItemDataProvider($checkout,$shopModel->id,$data),
        'template'=>'{items}',
        'itemView'=>'carts.views.management._cart_item_quickview',
        'viewData'=>[
            'checkout'=>$checkout,
            'shopModel'=>$shopModel,
            'queryParams'=>$queryParams,
        ],
        'htmlOptions'=>['class'=>'cart-shipping'],
    ]);

echo CHtml::tag('div',['class'=>'offcanvas-cart-shipping'],Sii::t('sii','Shipping: {shipping_name}',['{shipping_name}'=>$shopModel->parseLanguageValue($this->cart->getShippingData($data)['shipping_name'],user()->getLocale())]));

echo CHtml::tag('div',['class'=>'offcanvas-cart-checkout'],CHtml::link(Sii::t('sii','Checkout'),'javascript:void(0);',['onclick'=>$checkoutScript,'class'=>'checkout-button ui-button']));
