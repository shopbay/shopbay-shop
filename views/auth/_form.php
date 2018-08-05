<div class="form">
    
    <div id="flash-bar">
        <?php $this->sflashwidget(array(get_class($model),'activate'));?>
    </div>    

    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'login-form',
            'action'=>url('login'),
            'enableAjaxValidation'=>false,
    )); ?>

    <div class="row">
        <?php //echo $form->label($model,'username',array('class'=>'form-label')); ?>
        <?php echo $form->textField($model,'username',array('class'=>'form-input','maxlength'=>32,'autofocus'=>'autofocus','placeholder'=>$model->getAttributeLabel('username'))); ?>
        <?php echo $form->error($model,'username'); ?>
    </div>

    <div class="row">
        <?php //echo $form->label($model,'password',array('class'=>'form-label')); ?>
        <?php echo $form->passwordField($model,'password',array('class'=>'form-input','maxlength'=>64,'placeholder'=>$model->getAttributeLabel('password'))); ?>
        <?php echo $form->error($model,'password'); ?>
    </div>

    <div class="row">
        <?php echo $form->hiddenField($model,'token'); ?>
        <?php echo CHtml::hiddenField('returnUrl',request()->getQuery('returnUrl')); ?>
        <?php echo CHtml::hiddenField('oauthClient',request()->getQuery('oauthClient')); ?>
        <input id="login-button" class="ui-button" name="login-button" type="submit" <?php echo $page->onPreview?'disabled':'';?> value="<?php echo Sii::t('sii','Log in');?>">
    </div>

    <div class="row">
        <span class="checkbox-wrapper">
            <?php echo $form->checkBox($model,'rememberMe',array('style'=>'width:auto;vertical-align:middle;margin-right:5px;')); ?>
            <?php echo $form->label($model,'rememberMe',array('class'=>'form-label','style'=>'display:inline;')); ?>
            <?php echo $form->error($model,'rememberMe'); ?>
        </span>
        <span class="form-link">
            <?php echo l(Sii::t('sii','Forgot password?'),$page->onPreview ? '#' : url('account/forgotpassword'),array('style'=>'float:right;'));?>
        </span>
    </div>

    <div class="row tos">
        <?php echo Sii::t('sii','By signing up, you agree to the {agreement}',['{agreement}'=>CHtml::link(Sii::t('sii','Terms of Service'),$page->appendExtraQueryParams(url('terms')))]); ?>
    </div>

    <?php $this->endWidget(); ?>

    
    <?php if ($this->allowOAuth && !$model->isActivateMode):?>
    <div class="link-container">
        <?php $this->widget('common.modules.accounts.oauth.widgets.OAuthWidget',array(
            'route'=>'account/authenticate',
            'iconOnly'=>false,
        )); ?>
    </div>    
    <?php endif;?>
    
    <p>
        <?php echo Sii::t('sii','Not yet have an account? {signup}',array('{signup}'=>CHtml::link(Sii::t('sii','Register here'),$page->appendExtraQueryParams(url('register')))));?>
    </p>
    
</div>
