<?php 
$this->widget('common.widgets.SDetailView', array(
    'data'=>array('item','price','quantity','subtotal'),
    'attributes'=>array(
        array(
            'name'=>'item',
            'template'=>'<div class="{class}"><span class="value">{value}</span></div>',
            'type'=>'raw',
            'value'=>user()->isAuthenticated?'<span class="shop">'.CHtml::link($order->shop->displayLanguageValue('name',user()->getLocale()),$order->shop->url,['target'=>'_blank']).'</span>':'',
            'cssClass'=>'item-column',    
            'visible'=>!user()->onShopScope(),
        ),
        array(
            'name'=>'price',
            'template'=>'<div class="{class}"><span class="value">{value}</span></div>',
            'value'=>Sii::t('sii','Price'),
            'cssClass'=>'price-column',
        ),
        array(
            'name'=>'quantity',
            'template'=>'<div class="{class}"><span class="value">{value}</span></div>',
            'value'=>Sii::t('sii','Quantity'),
            'cssClass'=>'quantity-column',
        ),
        array(
            'name'=>'subtotal',
            'template'=>'<div class="{class}"><span class="value">{value}</span></div>',
            'value'=>Sii::t('sii','Subtotal'),
            'cssClass'=>'subtotal-column',
        ),
    ),
    'htmlOptions'=>array('class'=>'order-item-header'),
));
