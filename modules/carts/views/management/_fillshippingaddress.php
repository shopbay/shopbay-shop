<?php 
$this->widget('common.widgets.SDetailView', array(
    'data'=>array('required'),
    'attributes'=>array(
        array(
            'name'=>'note',
            'template'=>'<div class="{class}"><span class="note">{value}</span></div>',
            'type'=>'raw',
            'value'=>Sii::t('sii','Fields with <span class="required">*</span> are required.'),
            'cssClass'=>'required-column',
        ),
    ),
    'htmlOptions'=>array('class'=>'cart-address-header'),
)); 

if (user()->isGuest)
    $this->renderPartial('_emailaddress_form',array('form'=>$form));

$this->renderPartial('_fillshippingaddress_form',array('form'=>$form));

$this->renderPartial('_cart',array('checkout'=>true,'shop'=>$shop));