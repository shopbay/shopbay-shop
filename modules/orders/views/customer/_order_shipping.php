<?php 
$this->widget($this->getModule()->getClass('listview'), 
    array(
        'dataProvider'=> $this->getOrderItemDataProvider($order,$data),
        'template'=>'{items}',
        'itemView'=>'_order_item',
        'htmlOptions'=>array('class'=>'order-shipping'),
    ));

$this->widget('common.widgets.SDetailView', array(
    'data'=>$data,
    'attributes'=>array(
        array(
            'type'=>'raw',
            'template'=>'<div class="{class}">{value}</div>',
            'value'=>$this->renderPartial('_order_shipping_remarks',array('order'=>$order,'shipping'=>$data),true),
            'cssClass'=>'remarks-column',
        ),
        array(
            'type'=>'raw',
            'template'=>'<div class="{class}">{value}</div>',
            'value'=>$this->renderPartial('_order_shipping_total',array('order'=>$order,'shipping'=>$data),true),
            'cssClass'=>'shipping-subtotal-column',
        ),        
    ),
    'htmlOptions'=>array('class'=>'order-total'),
));
