<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', [
            'id'=>'contact-form',
    ]); ?>

        <p class="note"><?php echo Sii::t('sii','Fields with <span class="required">*</span> are required.');?></p>

        <?php echo $form->errorSummary($model); ?>

        <div class="row">
            <div class="column">
                <?php echo $form->labelEx($model,'name'); ?>
                <?php echo $form->textField($model,'name',['size'=>100,'maxlength'=>100]); ?>
                <?php //echo $form->error($model,'name'); ?>
            </div>
            <div class="column">
                <?php echo $form->labelEx($model,'email'); ?>
                <?php echo $form->textField($model,'email',['size'=>100,'maxlength'=>100]); ?>
                <?php //echo $form->error($model,'email'); ?>
            </div>
        </div>

        <div class="row" style="clear:both;">
                <?php echo $form->labelEx($model,'subject'); ?>
                <?php echo $form->textField($model,'subject',['size'=>85,'maxlength'=>100]); ?>
                <?php //echo $form->error($model,'subject'); ?>
        </div>

        <div class="row">
                <?php echo $form->labelEx($model,'body'); ?>
                <?php echo $form->textArea($model,'body',['maxlength'=>1000, 'rows'=>6, 'cols'=>73,'style'=>'border-color: #B4B4B4']); ?>
                <?php //echo $form->error($model,'body'); ?>
        </div>

        <?php if(CCaptcha::checkRequirements()): ?>
        <div class="row">
            <div class="column">
                <?php $this->widget('CCaptcha',[
                        'buttonType'=>'button',
                        'captchaAction'=> 'captcha',
                        'clickableImage'=>true,
                        'showRefreshButton'=>false,
                        'imageOptions'=>['style'=>'cursor:pointer','title'=>Sii::t('sii','Click to Refresh')],
                    ]); 
                ?>
            </div>
            <div class="column">
                <small>
                    <?php echo Sii::t('sii','Please enter the letters (case-insensitive) as they are shown in the image above.');?>
                    <br><?php echo Sii::t('sii','If you cannot see the image clearly, click on the image to get a new one.');?>
                </small>
                <br><?php //echo CHtml::error($model,'verify_code'); ?>
                <?php echo CHtml::activeTextField($model, 'verify_code'); ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="row buttons" style="padding-top:20px;clear:both">
        <?php 
            $this->widget('zii.widgets.jui.CJuiButton',[
                    'name'=>'sendButton',
                    'buttonType'=>'button',
                    'caption'=>Sii::t('sii','Send'),
                    'value'=>'actionbtn',
                    'onclick'=>'js:function(){submitform(\'contact-form\');}',
                ]);
         ?>                
        </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->