<div class="form-wrapper shop rounded">
    
    <div class="registered-customers">
        
        <div class="form form-container" >

            <h1><?php echo Sii::t('sii','Registered Customers');?></h1>

            <p><?php echo Sii::t('sii','Sign in to speed up the checkout process');?></p>
            
            <div id="flash-bar">
                <?php $this->sflashwidget(array(get_class($model)));?>
            </div>    

            <?php $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'login-form',
                    'action'=>request()->getHostInfo().'/account/authenticate/login',
                    'enableAjaxValidation'=>false,
            )); ?>

            <div class="form-row">
                <?php //echo $form->label($model,'username',array('class'=>'form-label')); ?>
                <?php echo $form->textField($model,'username',array('class'=>'form-input','maxlength'=>32,'autofocus'=>'autofocus','placeholder'=>$model->getAttributeLabel('username'))); ?>
                <?php echo $form->error($model,'username'); ?>
            </div>

            <div class="form-row">
                <?php //echo $form->label($model,'password',array('class'=>'form-label')); ?>
                <?php echo $form->passwordField($model,'password',array('class'=>'form-input','maxlength'=>64,'placeholder'=>$model->getAttributeLabel('password'))); ?>
                <?php echo $form->error($model,'password'); ?>
            </div>

            <div class="form-row">
                <?php echo CHtml::hiddenField('returnUrl',request()->getQuery('returnUrl')); ?>
                <?php $this->widget('zii.widgets.jui.CJuiButton',
                            array(
                                'name'=>'login-button',
                                'buttonType'=>'submit',
                                'caption'=>isset($model->title)?$model->title:Sii::t('sii','Log in'),
                                'value'=>'loginBtn',
                                'htmlOptions'=>array('class'=>'ui-button','style'=>'margin-top:10px;'),
                                )
                            ); 
                ?>
            </div>

            <div class="form-row" style="margin-top:10px;">
                <span class="checkbox-wrapper" style="float:left;">
                    <?php echo $form->checkBox($model,'rememberMe',array('style'=>'vertical-align:middle;margin-right:5px;')); ?>
                    <?php echo $form->label($model,'rememberMe',array('class'=>'form-label','style'=>'display:inline;')); ?>
                    <?php echo $form->error($model,'rememberMe'); ?>
                </span>
                <span class="form-link forgot-password">
                    <?php echo l(Sii::t('sii','Forgot password?'),url('account/management/forgotpassword'),array('style'=>'float:right;'));?>
                </span>
            </div>

            <?php $this->endWidget(); ?>

        </div>
        <?php if ($this->allowOAuth && !$model->isActivateMode):?>
        <div class="link-container">
            <?php $this->widget('common.modules.accounts.oauth.widgets.OAuthWidget',array(
                'route'=>'account/authenticate',
                'iconOnly'=>false,
            )); ?>
             <div class="form-row tos">
                <?php echo $model->getAttributeLabel('acceptTOS'); ?>
            </div>
       </div>    
        <?php endif;?>

        <div class="link-container">

            <span class="form-link">
                <?php echo Sii::t('sii','Not yet have an account? {signup}',array('{signup}'=>CHtml::link(Sii::t('sii','Sign up here'),'javascript:void(0);',array('onclick'=>'signup("'.$this->authHostInfo.'");'))));?>
            </span>

        </div>

    </div>

    <div class="separator">
        <span>
           | <?php echo Sii::t('sii','Or');?> |
        </span>
    </div>
    
    <div class="guest-customers">
        <h1><?php echo Sii::t('sii','Guest Customers');?></h1>
            
        <p><?php echo Sii::t('sii','Continue to checkout without signing in.');?></p>
        
        <div class="checkout-button">
            <?php echo CHtml::link(Sii::t('sii','Checkout as guest'), $this->guestCheckoutUrl); ?>
        </div>
        
    </div>
    
    
</div>
