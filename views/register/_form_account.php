<div class="form customer-account-form">

    <div class="form-row">
        <?php echo $form->labelEx($model,'email',array('class'=>'form-label')); ?>
        <?php echo $form->textField($model,'email',array('class'=>'form-input','maxlength'=>100,'placeholder'=>$model->getAttributeLabel('email'))); ?>
        <?php echo $form->error($model,'email'); ?>
    </div>

    <div class="form-row">
        <?php echo $form->labelEx($model,'password',array('class'=>'form-label')); ?>
        <?php echo $form->passwordField($model,'password',array('class'=>'form-input','maxlength'=>32,'placeholder'=>$model->getAttributeLabel('password'))); ?>
        <?php echo $form->error($model,'password'); ?>
    </div>

    <div class="form-row">
        <?php echo $form->labelEx($model,'confirmPassword',array('class'=>'form-label')); ?>
        <?php echo $form->passwordField($model,'confirmPassword',array('class'=>'form-input','maxlength'=>32,'placeholder'=>$model->getAttributeLabel('confirmPassword'))); ?>
        <?php echo $form->error($model,'confirmPassword'); ?>
    </div>

    <?php if(CCaptcha::checkRequirements()): ?>
    <div class="form-row">
        <table>
            <tr>
                <td>
                    <?php $this->widget('CCaptcha',array(
                            'id'=>'signup-captcha',
                            'buttonType'=>'button',
                            'captchaAction'=> 'captcha',
                            'clickableImage'=>true,
                            'showRefreshButton'=>false,
                            'imageOptions'=>array('style'=>'cursor:pointer','title'=>Sii::t('sii','Click to Refresh')),
                        )); 
                    ?>
                </td>
                <td>
                    <div style="display:inline">
                        <small>
                            <?php echo Sii::t('sii','Please enter the letters (case-insensitive) at shown at left. Click on the image to get a new one.');?>
                        </small>
                        <br>
                        <?php echo CHtml::activeTextField($model, 'verify_code'); ?>
                        <?php echo $form->error($model,'verify_code'); ?>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <?php endif; ?>


</div>