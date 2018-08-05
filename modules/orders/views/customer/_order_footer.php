<?php
$this->widget('common.widgets.SDetailView', array(
    'data'=>array('item_count','confirm_info','grand_total'),
    'attributes'=>array(
        array(
            'name'=>'item',
            'template'=>'<div class="{class}">{value}</div>',
            'type'=>'raw',
            'value'=>Sii::t('sii','Total <span class="items-counter">{n}</span> item|Total <span class="items-counter">{n}</span> items',array($order->item_count)),
            'cssClass'=>'actions-column',
        ), 
        array(
            'name'=>'item',
            'template'=>'<div class="{class}">{value}</div>',
            'type'=>'raw',
            'value'=>$this->renderPartial('_order_shipping_address',array('order'=>$order),true).
                     $this->renderPartial('_order_payment_method',array('order'=>$order),true),
            'cssClass'=>'info-column',
        ),           
        array(
            'type'=>'raw',
            'template'=>'<div class="{class}">{value}</div>',
            'value'=>$this->renderPartial('_order_total',array('order'=>$order),true),
            'cssClass'=>'grand-total-column',
        ),        
    ),
    'htmlOptions'=>array('class'=>'order-footer'),
));
