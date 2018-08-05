<?php 
$this->widget('common.widgets.SDetailView', array(
    'data'=>array('checkbox'),
    'attributes'=>array(
        array(
            'type'=>'raw',
            'template'=>'<div class="copyaddr">{value}<span id="copyAddrErr" style="display:none"></span><span id="copyAddrMsg">'.Sii::t('sii','Use the address in my account').'</span></div>',
            'value'=>CHtml::checkBox('shipping_copyaddr',false,array('onchange'=>'copyaddress()','class'=>'copyaddr-checkbox')),
            'cssClass'=>'checkbox-column',
            'visible'=>user()->isAuthenticated,
        ),
    ),
    'htmlOptions'=>array('class'=>'cart-address-header'),
)); 

$this->widget('common.widgets.SDetailView', array(
    'data'=>$form,
    'attributes'=>array(
        array(
            'name'=>'recipient',
            'template'=>'<div class="{class}">{label}{value}</div>',
            'type'=>'raw',
            'label'=>CHtml::activeLabelEx($form,'recipient'),
            'value'=>CHtml::activeTextField($form,'recipient',array('size'=>40,'maxlength'=>32)),
            'cssClass'=>'recipient-column',
        ),
        array(
            'name'=>'mobile',
            'template'=>'<div class="{class}">{label}{value}</div>',
            'type'=>'raw',
            'label'=>CHtml::activeLabelEx($form,'mobile'),
            'value'=>CHtml::activeTextField($form,'mobile',array('size'=>18,'maxlength'=>20)),
            'cssClass'=>'mobile-column',
        ),
        array(
            'name'=>'address1',
            'template'=>'<div class="{class}">{label}{value}</div>',
            'type'=>'raw',
            'label'=>CHtml::activeLabelEx($form,'address1'),
            'value'=>CHtml::activeTextField($form,'address1',array('size'=>40,'maxlength'=>100)),
            'cssClass'=>'address-column',
        ),   
        array(
            'name'=>'postcode',
            'template'=>'<div class="{class}">{label}{value}</div>',
            'type'=>'raw',
            'label'=>CHtml::activeLabelEx($form,'postcode'),
            'value'=>CHtml::activeTextField($form,'postcode',array('size'=>18,'maxlength'=>20)),
            'cssClass'=>'postcode-column',
        ),         
        array(
            'name'=>'address2',
            'template'=>'<div class="{class}"><label class="empty-label"></label>{value}</div>',
            'type'=>'raw',
            'value'=>CHtml::activeTextField($form,'address2',array('size'=>40,'maxlength'=>100)),
            'cssClass'=>'address-column',
        ),   
        array(
            'name'=>'city',
            'template'=>'<div class="{class}">{label}{value}</div>',
            'type'=>'raw',
            'label'=>CHtml::activeLabelEx($form,'city'),
            'value'=>CHtml::activeTextField($form,'city',array('size'=>18,'maxlength'=>40)),
            'cssClass'=>'city-column',
        ),         
        array(
            'name'=>'country',
            'template'=>'<div class="{class}">{label}{value}</div>',
            'type'=>'raw',
            'label'=>CHtml::activeLabelEx($form,'country'),
            'value'=>CHtml::activeDropDownList($form,
                                               'country', 
                                               SLocale::getCountries(),
                                               array('prompt'=>'',
                                                      'class'=>'chzn-select-country country',
                                                      'data-placeholder'=>Sii::t('sii','Select Country'),
                                                    )),
            'cssClass'=>'country-column',
        ),         
        array(
            'name'=>'state',
            'template'=>'<div class="{class}">{label}{value}</div>',
            'type'=>'raw',
            'label'=>CHtml::activeLabelEx($form,'state'),
            'value'=>CHtml::activeDropDownList($form,
                                               'state',
                                                SLocale::getStates($form->country),
                                                array('class'=>'chzn-select-state state',
                                                      'prompt'=>'',
                                                      'data-placeholder'=>Sii::t('sii','Select State')
                                                )),
            //'value'=>CHtml::activeTextField($form,'state',array('size'=>18,'maxlength'=>40)),
            'cssClass'=>'state-column',
        ),           
        array(
            'name'=>'note',
            'template'=>'<div class="{class}">{label}{value}</div>',
            'type'=>'raw',
            'label'=>CHtml::activeLabelEx($form,'note'),
            'value'=>CHtml::activeTextArea($form,'note',array('rows'=>2,'maxlength'=>100)),
            'cssClass'=>'note-column',
        ),          
    ),
    'htmlOptions'=>array('class'=>'cart-address-form'),
)); 


$this->widget('SStateDropdown',[
    'stateGetActionUrl'=>url('account/profile/stateget'),
    'countryFieldId'=>'CartAddressForm_country',
    'stateFieldId'=>'CartAddressForm_state',
]);