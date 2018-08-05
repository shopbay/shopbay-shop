<?php 
$this->widget('common.widgets.SDetailView', array(
    'data'=>$data,
    'attributes'=>array(
        array(
            'type'=>'raw',
            'template'=>'<div class="{class}">{value}</div>',
            'value'=>$data->hasAffinity()?'':CHtml::checkbox($this->getCartName($shopModel->id).'-checkbox', $data->getCheckout(), 
                                     array('id'=>'cart-checkbox',
                                           'value'=>$data->getKey(),
                                           'pid'=>$data->getProduct(),
                                           'onchange'=>'itemcheckout($(this))')),
            'cssClass'=>'checkbox-column',
            'visible'=>!$checkout,
        ),
        array(
            'type'=>'raw',
            'template'=>'<div class="{class}">{value}</div>',
            'value'=>$this->renderView('carts.cartiteminfo',array('data'=>$data,'checkout'=>$checkout,'queryParams'=>isset($queryParams)?$queryParams:[]),true),
            'cssClass'=>'item-column'.($checkout?' checkout':''),
        ),
        array(
            'type'=>'raw',
            'template'=>'<div class="{class}"><div class="value">{value}</div></div>',
            'value'=>$this->renderView('carts.cartitemprice',array('data'=>$data),true),
            'cssClass'=>'price-column',
        ),
        array(
            'type'=>'raw',
            'template' =>'<div class="{class}"><div id="item_quantity'.($data->hasAffinity()?'_'.$data->getAffinityKey():'').'" class="value">{value}</div></div>',
            'value'=>$checkout||$data->hasAffinity()?$data->getQuantity():CHtml::activeDropDownList(
                                                                $data,
                                                                'quantity', 
                                                                $data->hasCampaign()?Helper::getQuantityList($data->getCampaignModel()->buy_x_qty,$shopModel->getCheckoutQuantityLimit(),$data->getCampaignModel()->buy_x_qty):Helper::getQuantityList(1,$shopModel->getCheckoutQuantityLimit(),1),
                                                                array('prompt'=>'',
                                                                      'class'=>'cart-item-quantity',
                                                                      'data-placeholder'=>'',
                                                                      'data-name'=>$data->getKey().'_quantity',
                                                                      'style'=>'width:45px;')),
            'cssClass'=>'quantity-column',
        ),
        array(
            'template'=>'<div class="{class}"><div id="subtotal'.($data->hasAffinity()?'_'.$data->getAffinityKey():'').'" class="value">{value}</div></div>',
            'value'=>$shopModel->formatCurrency($data->getTotalPrice()),
            'cssClass'=>'subtotal-column',
        ),
    ),
    'htmlOptions'=>array('class'=>'cart-item'.($data->hasAffinity()?' promotion-item':'')),
)); 