<?php
$this->widget('common.widgets.SDetailView', array(
    'data'=>array('checkbox','required'),
    'attributes'=>array(
        array(
            'name'=>'note',
            'template'=>'<div class="{class}"><span class="note">{value}</span></div>',
            'type'=>'raw',
            'value'=>Sii::t('sii','This email address will be used for receipt of the order confirmation and shipping notifications.'),
            'cssClass'=>'email-column',
        ),
    ),
    'htmlOptions'=>array('class'=>'cart-address-header'),
)); 

$this->widget('common.widgets.SDetailView', array(
    'data'=>$form,
    'attributes'=>array(
        array(
            'name'=>'email',
            'template'=>'<div class="{class}">{label}{value}</div>',
            'type'=>'raw',
            'label'=>CHtml::activeLabelEx($form,'email',['required'=>true]),
            'value'=>CHtml::activeTextField($form,'email',array('size'=>40,'maxlength'=>32)),
            'cssClass'=>'email-column',
        ),
    ),
    'htmlOptions'=>array('class'=>'cart-address-form'),
)); 