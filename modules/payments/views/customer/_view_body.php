<?php 
$this->widget('common.widgets.SDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        array('name'=>'reference_no','type'=>'raw','value'=>CHtml::link(CHtml::encode($model->reference_no), url('order/view/'.$model->reference_no))),
        array('name'=>'create_time','value'=>$model->formatDatetime($model->create_time,true)),
        array('name'=>'type','value'=>$model->getTypeDesc()),
        array('name'=>'method','value'=>$model->getPaymentMethodName(user()->getLocale())),
        array('name'=>'amount','value'=>$model->formatCurrency($model->amount,$model->currency)),
        array('name'=>'trace_no','type'=>'raw','value'=>$model->getTraceNo(),'cssClass'=>'trace-no'),
    ),
    'htmlOptions'=>array('class'=>'detail-view solid'),
));