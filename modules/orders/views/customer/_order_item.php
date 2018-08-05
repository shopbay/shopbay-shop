<?php 
$this->widget('common.widgets.SDetailView', array(
    'data'=>$data,
    'attributes'=>array(
        array(
            'type'=>'raw',
            'template'=>'<div class="{class}">{value}</div>',
            'value'=>$this->renderPartial('_order_item_info',array('data'=>$data),true),
            'cssClass'=>'item-column',
        ),
        array(
            'type'=>'raw',
            'template'=>'<div class="{class}"><div class="value">{value}</div></div>',
            'value'=>$this->renderPartial('_order_item_price',array('data'=>$data),true),
            'cssClass'=>'price-column',
        ),
        array(
            'type'=>'raw',
            'template' =>'<div class="{class}"><div class="value">{value}</div></div>',
            'value'=>$data->quantity,
            'cssClass'=>'quantity-column',
        ),
        array(
            'template'=>'<div class="{class}"><div class="value">{value}</div></div>',
            'value'=>$data->formatCurrency($data->total_price),
            'cssClass'=>'subtotal-column',
        ),
    ),
    'htmlOptions'=>array('class'=>'order-item'.($data->hasAffinity()?' promotion':'')),
)); 
