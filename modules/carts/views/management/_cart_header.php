<?php 
$this->widget('common.widgets.SDetailView', array(
    'data'=>array('checkbox','item','price','quantity','subtotal'),
    'attributes'=>array(
        array(
            'name'=>'checkbox',
            'template'=>'<div class="{class}"><span class="value">{value}</span></div>',
            'type'=>'raw',
            'value'=>'',
            'cssClass'=>'checkbox-column',
            'visible'=>!$checkout,
        ),
        array(
            'name'=>'item',
            'template'=>'<div class="{class}"><span class="value">{value}</span></div>',
            'type'=>'raw',
            'value'=>$checkout?'<span class="shop">'.$this->cart->getShop($shop)->displayLanguageValue('name',user()->getLocale()).'</span>':
                     Sii::t('sii','You have <span class="items-counter">{n}</span> item in this cart|You have <span class="items-counter">{n}</span> items in this cart',
                     array($this->cart->getCount($shop))),
            'cssClass'=>'item-column'.($checkout?' checkout':''),     
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
    'htmlOptions'=>array('class'=>'cart-item-header'),
)); 