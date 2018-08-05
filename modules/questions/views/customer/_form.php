<?php cs()->registerScript('chosen','$(\'.chzn-select-tags\').chosen();',CClientScript::POS_END);?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'question-form',
        'enableAjaxValidation'=>false,
)); ?>

        <?php if ($model->isNewRecord):?>
        <p class="note"><?php echo Sii::t('sii','Fields with <span class="required">*</span> are required.');?></p>
        <?php endif;?>

        <?php //echo $form->errorSummary($model,null,null,array('style'=>'width:57%')); ?>

        <div class="row">
            <?php echo $form->labelEx($model,'title'); ?>
            <?php echo $form->textField($model,'title',array('size'=>120,'maxlength'=>256)); ?>
            <?php //echo $form->error($model,'title'); ?>
        </div>

        <div class="row" style="margin-top:10px">
            <?php echo $form->labelEx($model,'question'); ?>
            <?php echo $form->textArea($model,'question',array('rows'=>6, 'style'=>'font-size:1.1em;')); ?>
            <?php echo $form->error($model,'question'); ?>
	</div>
        
        <div class="row" style="margin-top:20px;">
            <?php echo $form->labelEx($model,'tags',array('style'=>'margin-bottom:5px;')); ?>
            <?php echo $form->dropDownList($model, 'tags',
                                           Tag::getList(user()->getLocale()), 
                                           array('prompt'=>'',
                                                 'class'=>'chzn-select-tags',
                                                 'multiple'=>true,
                                                 'data-placeholder'=>Sii::t('sii','Select Tags'),
                                                 'style'=>'width:80%;'));
            ?>
            <?php echo $form->error($model,'tags'); ?>
        </div>

        <div class="row" style="margin-top:20px;">
            <?php 
                $this->widget('zii.widgets.jui.CJuiButton',
                    array(
                        'name'=>'actionButton',
                        'buttonType'=>'button',
                        'caption'=> Sii::t('sii','Submit'),
                        'value'=>'actionbtn',
                        'onclick'=>'js:function(){submitform(\'question-form\');}',
                        )
                );
             ?>
        </div>

<?php $this->endWidget(); ?>

</div><!-- form -->