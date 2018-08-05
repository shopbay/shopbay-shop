<div class="form">
    
    <div id="flash-bar">
        <?php //$this->sflashwidget(get_class($model));?>
    </div>    
    
    <p>
        <?php echo Sii::t('sii','By creating an account you will be able to speed up your checkout process, follow up closely on your order status, and maintain your orders history.');?>
    </p>
        
    <p>
        <?php echo Sii::t('sii','Already have an account? {signin}',array('{signin}'=>CHtml::link(Sii::t('sii','Log in'),$page->appendExtraQueryParams(url('login'))))); ?>            
    </p>
    
    <br>
    <p>
        <?php echo Sii::t('sii','Fields with <span class="required">*</span> are required.');?>
    </p>
        
    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'register_form',
            'action'=>url('register'),
            'enableAjaxValidation'=>true,
    )); ?>
        
    <?php $this->renderPartial('_form_account',['form'=>$form,'model'=>$model]);?>

    <div class="customer-form-separator"><!-- empty --></div>
    
    <?php $this->renderPartial('_form_address',['form'=>$form,'model'=>$model]);?>

    <div class="row button">
        <?php //echo $form->checkBox($model,'accept',array('style'=>'margin-left:10px;')); ?>
        <!--<span class="form-link">
            <?php //todo for newsletter subscription
                  //echo l($model->getAttributeLabel('accept'),url('terms'));?>
        </span>-->
        <input id="register-button" class="ui-button" name="register-button" <?php echo $page->onPreview?'disabled':'';?> type="submit" value="<?php echo Sii::t('sii','Register');?>">
    </div>

    <div class="row tos">
        <?php echo Sii::t('sii','By signing up, you agree to the {agreement}',['{agreement}'=>CHtml::link(Sii::t('sii','Terms of Service'),$page->appendExtraQueryParams(url('terms')))]); ?>
    </div>
    
    <?php $this->endWidget(); ?>
    
</div>