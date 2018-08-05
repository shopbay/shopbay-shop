<?php 
$this->widget('common.widgets.SDetailView', [
    'data'=>$data,
    'attributes'=>[
        [
            'type'=>'raw',
            'template'=>'<div class="{class}">{value}</div>',
            'value'=>$this->renderPartial('application.modules.carts.views.management._cart_item_info_quickview',[
                'data'=>$data,
                'checkout'=>$checkout,
                'queryParams'=>!empty($queryParams)?$queryParams:[],
            ],true),
            'cssClass'=>'item-column'.($checkout?' checkout':''),
        ],
    ],
    //data-item-key is used to formulate checkout url @see carts.js offcanvascheckout()
    'htmlOptions'=>['data-item-key'=>$data->getKey(),'class'=>'cart-item'.($data->hasAffinity()?' promotion-item':'').' '.$data->getKey()],//put key as css class for removal use, @see carts.js
]); 