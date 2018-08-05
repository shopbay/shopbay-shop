<?php
$this->widget('common.widgets.SDetailView', array(
    'data'=>$shop,
    'attributes'=>array(
        array(
            'type'=>'raw',
            'template'=>'<div class="{class}">{value}</div>',
            'value'=>$this->renderView('carts.cartactions',array('shop'=>$shop),true),
            'cssClass'=>'actions-column',
            'visible'=>!$checkout,
        ),
        array(
            'name'=>'item',
            'template'=>'<div class="{class}">{value}</div>',
            'type'=>'raw',
            'value'=>Sii::t('sii','Total <span class="items-counter">{n}</span> item|Total <span class="items-counter">{n}</span> items',
                     array($this->cart->getCheckoutCount($shop))),
            'cssClass'=>'actions-column',
            'visible'=>$checkout,
        ), 
//        array(
//            'name'=>'item',
//            'template'=>'<div class="{class}">{value}</div>',
//            'type'=>'raw',
//            'value'=>isset($confirm)?$this->renderView('carts.confirmaddress',array(),true).$this->renderView('carts.confirmpayment',array(),true):'',
//            'cssClass'=>'info-column',
//        ),           
//        array(
//            'name'=>'item',
//            'template'=>'<div class="{class}">{value}</div>',
//            'type'=>'raw',
//            'value'=>$this->renderView('carts.cartpromocode',array('shop'=>$shop,'promocode'=>$this->cart->getPromocode($shop)),true),
//            'cssClass'=>'promocode-column',
//            'visible'=>!$checkout&&$this->cart->getShop($shop)->hasPromocodes(),
//        ),           
//        array(
//            'type'=>'raw',
//            'template'=>'<div class="{class}">{value}</div>',
//            'value'=>$this->renderView('carts.carttotal',array('shop'=>$shop),true),
//            'cssClass'=>'grand-total-column',
//        ),        
    ),
    'htmlOptions'=>array('class'=>'cart-footer'),
));
