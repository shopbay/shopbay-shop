<?php 
$this->widget($this->getModule()->getClass('listview'), 
    array(
        'dataProvider'=> $this->getCartItemDataProvider($checkout,$shop,$data),
        'template'=>'{items}',
        'itemView'=>$this->module->getView('carts.cartitem'),
        'viewData'=>array('checkout'=>$checkout,'shopModel'=>$this->cart->getShop($shop),'queryParams'=>isset($queryParams)?$queryParams:[]),
        'htmlOptions'=>array('class'=>'cart-shipping'),
    ));

$this->widget('common.widgets.SDetailView', array(
    'data'=>$data,
    'attributes'=>array(
        array(
            'type'=>'raw',
            'template'=>'<div class="{class}">{value}</div>',
            'value'=>$this->renderView('carts.cartshippingremarks',array(
                'shippingData'=>(object)$this->cart->getCheckoutSubTotalByShipping($data),
                'shopModel'=>$this->cart->getShop($shop))
                ,true),
            'cssClass'=>'remarks-column',
        ),
        array(
            'type'=>'raw',
            'template'=>'<div class="{class}">{value}</div>',
            'value'=>$this->renderView('carts.cartshippingtotal',array('shop'=>$shop,'shipping'=>$data),true),
            'cssClass'=>'shipping-subtotal-column',
        ),        
    ),
    'htmlOptions'=>array('class'=>'cart-total'),
));

cs()->registerScript('refreshform','refreshform();',CClientScript::POS_END);