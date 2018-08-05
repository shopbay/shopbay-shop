<?php 
if(isset($button))
    $form = $this->beginWidget('CActiveForm', array(
        'id'=>'payment-form',
        'enableAjaxValidation'=>false,
    )); 
?>
<div class="cart-payment-form">
    <div class="method">
        <?php echo CHtml::activeHiddenField($model, 'method');?>
        <?php foreach ($model->getPaymentMethods() as $method): ?>
        <table style="margin-bottom:0px;display: inline-table;">
            <tr>
                <td width="5%">
                    <?php 
                        $methodUrl = str_replace('{id}', $method->id, $url);
                        echo CHtml::activeRadioButton($model,'id', 
                            array('value'=>$method->id,
                                  'uncheckValue'=>null,
                                  'onclick'=>'javascript:selectmethod('.$method->id.',\''.$methodUrl.'\');',
                                  'style'=>'vertical-align:top'));?>
                </td>
                <td class="payment-method-desc">
                    <?php 
                        if ($method->method==PaymentMethod::PAYPAL_EXPRESS_CHECKOUT||$method->method==PaymentMethod::BRAINTREE_PAYPAL)
                            echo $this->renderPartial('common.modules.payments.views.logo.paypal',[],true);
                        else
                            echo $method->getMethodName(user()->getLocale());
                    ?>
                </td>
            </tr>
        </table>
        <?php endforeach;?>
    </div>
    <div class="description">
        <?php if (isset($errorSummary))
                  echo CHtml::errorSummary($model); 
        ?>
        <?php $model->renderTips();?>
        <?php if(isset($captcha)&& CCaptcha::checkRequirements()): ?>
        <div id="captcha" style="display:<?php echo $model->method==PaymentMethod::UNDEFINED?'none':'block';?>;margin-top:10px;">
                <?php $this->widget('CCaptcha',array(
                        'buttonType'=>'button',
                        'captchaAction'=> 'captcha',
                        'clickableImage'=>true,
                        'showRefreshButton'=>false,
                        'imageOptions'=>array('style'=>'cursor:pointer','title'=>Sii::t('sii','Click to Refresh')),
                    )); 
                ?>
                <br/>
                <small>
                    <?php echo Sii::t('sii','Please enter the letters (case-insensitive) as they are shown in the image above.');?>
                    <br><?php echo Sii::t('sii','If you cannot see the image clearly, click on the image to get a new one.');?>
                </small>
                <br><?php echo CHtml::error($model,'verify_code'); ?>
                <?php echo CHtml::activeTextField($model, 'verify_code'); ?>
                <br/>
        </div>
        <?php endif; ?>

        <?php if(isset($button)): ?>
        <div style="padding:25px 0px;">
            <?php $this->widget('zii.widgets.jui.CJuiButton',array(
                    'name'=>'paymentbutton',
                    'buttonType'=>'button',
                    'caption'=>Sii::t('sii','Make Payment'),
                    'value'=>'btn',
                    'htmlOptions'=>array(
                        'disabled'=>$model->method==PaymentMethod::UNDEFINED?true:false,
                        'style'=>$model->method==PaymentMethod::UNDEFINED?'display:none':''),
                    ));
                ?>
        </div>
        <?php endif; ?>

    </div>

</div>

<?php if(isset($button))
        $this->endWidget(); 
