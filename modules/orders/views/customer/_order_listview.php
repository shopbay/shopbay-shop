<?php 
$this->widget('common.widgets.SDetailView', array(
    'data'=>$data,
    'htmlOptions'=>array('class'=>'list-box float-image'),
    'attributes'=>array(
        array(
            'type'=>'raw',
            'template'=>'<div class="status">{value}</div>',
            'value'=>Helper::htmlColorText($data->getStatusText(),false),
        ),
        array(
            'type'=>'raw',
            'template'=>'<div class="action">{value}</div>',
            'value'=>$this->widget('zii.widgets.CMenu', array(
                'encodeLabel'=>false,
                'htmlOptions'=>array('class'=>'shortcuts'),
                'items'=>array(
                    array('label'=>SButtonColumn::getButtonLabel('contact'), 'url'=>$data->contactMerchantUrl),
                ),
            ),true),
        ),
        array(
            'type'=>'raw',
            'template'=>'<div class="image">{value}</div>',
            'value'=>$this->widget($this->module->getClass('listview'),array(
                        'dataProvider'=> new CArrayDataProvider($data->items),
                        'template'=>'{items}',
                        'emptyText'=>'',
                        'itemView'=>$this->module->getView('orders.orderproduct'),
                    ),true),
        ),
        array(
            'type'=>'raw',
            'template'=>'{value}',
            'value'=>$this->widget('common.widgets.SDetailView', array(
                'data'=>$data,
                'htmlOptions'=>array('class'=>'data'),
                'attributes'=>array(
                    array(
                        'type'=>'raw',
                        'template'=>'<div class="heading-element">{value}</div>',
                        'value'=>CHtml::link(CHtml::encode($data->order_no), $data->viewUrl),
                    ),
                    array(
                        'type'=>'raw',
                        'template'=>'<div class="element">{value}</div>',
                        'value'=>'<strong>'.CHtml::encode($data->getAttributeLabel('grand_total')).'</strong>'.
                                 CHtml::encode($data->formatCurrency($data->grand_total)),
                    ),        
                    array(
                        'type'=>'raw',
                        'template'=>'<div class="element" style="color:orangered">{value}</div>',
                        'value'=>'<strong>'.Sii::t('sii','Refund').'</strong>'.
                                 CHtml::encode($data->formatCurrency($data->refundTotal)),
                        'visible'=>$data->refundTotal>0,
                    ),        
                    array(
                        'type'=>'raw',
                        'template'=>'<div class="element">{value}</div>',
                        'value'=>Helper::prettyDate($data->create_time),
                    ),    
                    array(
                        'type'=>'raw',
                        'template'=>'<div class="button element">{value}</div>',
                        'value'=>TaskBaseController::getWorkflowButtons($data),
                    ),        
                ),
            ),true),
        ),        
    ),
)); 