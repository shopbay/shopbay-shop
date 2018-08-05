<?php 
$form=$this->beginWidget('CActiveForm', array(
    'id'=>$model->id,
    'enableAjaxValidation'=>false,
)); 

echo $form->hiddenField($model,'obj_type'); 
echo $form->hiddenField($model,'obj_id'); 
echo $form->hiddenField($model,'askUrl'); 

echo CHtml::openTag('div',array('class'=>'question-form-wrapper'));

echo CHtml::activetextArea($model,'question',array('placeholder'=>Sii::t('sii','Post a question'),'cols'=>50,'style'=>'overflow-y:auto','disabled'=>user()->isGuest?true:false));

echo CHtml::openTag('div',array('class'=>'checkbox-wrapper'));
echo CHtml::activeCheckbox($model,'type',array('uncheckValue'=>'0'));
echo CHtml::tag('span',['class'=>'checkbox-message'],$model->getAttributeLabel('type'));
echo CHtml::closeTag('div');

$this->widget('zii.widgets.jui.CJuiButton',array(
                'name'=>'question-button',
                'buttonType'=>'button',
                'caption'=>Sii::t('sii','Post'),
                'value'=>'questionbtn',
                'htmlOptions'=>array('style'=>'margin-left:10px'),
                'options'=>array('disabled'=>isset($preview)?true:false),
                'htmlOptions'=>array('form'=>$model->id,'class'=>'question-button ui-button'),
                'onclick'=>'js:function(){'.$model->formScript.'}',
            )); 

echo CHtml::error($model,'question',array('style'=>'color:red'));

echo CHtml::closeTag('div');

$this->endWidget();
    