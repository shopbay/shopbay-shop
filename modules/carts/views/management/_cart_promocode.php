<?php 
$textFieldName = 'promocode_'.hash('crc32b',$shop);
$foundSpan = CHtml::tag('span',array('class'=>'found'),'<i class="fa fa-check"></i>');
$this->widget('common.widgets.SDetailView', array(
    'data'=>array('promocode'),
    'attributes'=>array(
        array(
            'name'=>'promocode',
            'template'=>'<div class="{class}"><div class="label">{label}</div><div>{value}</div></div>',
            'type'=>'raw',
            'label'=>Sii::t('sii','Enter Promocode'),
            'value'=>CHtml::textField($textFieldName,$promocode!=null?$promocode:'',array('placeholder'=>'','maxlength'=>12,'data-shop'=>$shop)).
                    ($promocode!=null?$foundSpan:''),
            'cssClass'=>'promocode-column',
        ),
    ),
    'htmlOptions'=>array('class'=>'cart-promocode','id'=>'cart_promocode'),
)); 

cs()->registerScript($textFieldName,'onpromocode(\''.$textFieldName.'\');',CClientScript::POS_END);