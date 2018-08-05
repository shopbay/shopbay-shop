<div class="form-wrapper">

    <h1><?php echo Sii::t('sii','Account Registration');?></h1>
    
    <div id="flash-bar">
        <?php   $message = Sii::t('sii','You only need to set password.');
                $message .= ' '.Sii::t('sii','Other account information are extracted from the shipping address you had previously filled for order {order_no}.',['{order_no}'=>$model->order_no]);
                echo $this->getFlashAsString('notice',$message,null);
        ?>
        <?php $this->sflashwidget(get_class($model));?>
    </div>    
    
    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'signup-customer-form',
            'action'=>url('signup/customer/order/'.$model->order_no),
            'enableAjaxValidation'=>true,
    )); ?>
        
    <?php $this->renderPartial('_form_account',['form'=>$form,'model'=>$model]);?>

    <div class="customer-form-separator"><!-- empty --></div>
    
    <?php $this->renderPartial('_form_address',['form'=>$form,'model'=>$model,'showAlias'=>true]);?>

    <div class="form-row">
        <?php 
            $this->widget('zii.widgets.jui.CJuiButton',array(
                'name'=>'signup-button',
                'buttonType'=>'button',
                'caption'=>Sii::t('sii','Register'),
                'value'=>'btn',
                'htmlOptions'=>['class'=>'ui-button'],
                'onclick'=>'js:function(){registercustomeraccount("'.$model->order_no.'");}',//this is reloaded from orders.js
            )); 
        ?>
    </div>

    <div class="form-row tos">
        <?php echo $model->getAttributeLabel('acceptTOS'); ?>
    </div>
    
    <?php $this->endWidget(); ?>
    
</div>