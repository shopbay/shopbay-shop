<div class="list-box">
<?php 
$this->widget('common.widgets.SDetailView', array(
        'data'=>$data,
        'htmlOptions'=>array('class'=>'data'),
        'attributes'=>array(
            array(
                'type'=>'raw',
                'template'=>'<div class="heading-element">{value}</div>',
                'value'=>CHtml::link(CHtml::encode($data->displayName().' '.$data->payment_no), $data->viewUrl),
            ),
            array(
                'type'=>'raw',
                'template'=>'<div class="element">{value}</div>',
                'value'=>'<strong>'.CHtml::encode($data->getAttributeLabel('reference_no')).'</strong>'.
                         CHtml::link(CHtml::encode($data->reference_no), url('order/view/'.$data->reference_no)),
            ),         
            array(
                'type'=>'raw',
                'template'=>'<div class="element">{value}</div>',
                'value'=>'<strong>'.CHtml::encode($data->getAttributeLabel('create_time')).'</strong>'.
                         CHtml::encode($data->formatDatetime($data->create_time,true)),
            ),         
            array(
                'type'=>'raw',
                'template'=>'<div class="element">{value}</div>',
                'value'=>'<strong>'.CHtml::encode($data->getAttributeLabel('amount')).'</strong>'.
                         CHtml::encode($data->formatCurrency($data->amount,$data->currency)),
            ),         
        ),
    ));
?>
</div>