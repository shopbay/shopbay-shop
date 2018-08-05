<?php //@todo is this old code? file in use or to remove? ?>
<p>
    <?php if ($method==PaymentMethod::ATM_CASH_BANK_IN):?>
        <?php echo Sii::t('sii','We will process your order upon successful payment verification.');?>
    <?php else: ?>
        <?php echo Sii::t('sii','We will now start to process your order.');?>
    <?php endif;?>
    
    <?php echo Sii::t('sii','Your order is currently in status {status}.',array('{status}'=>Process::getHtmlDisplayText(Process::PROCESSIMG)));?>
    <br>
    <?php echo Sii::t('sii','You can track the status of your order and purchased items at ');?>
    <a style="position: relative;top: 0;right: 0;text-decoration: underline;" href="<?php echo url('orders/customer');?>"><?php echo Sii::t('sii','My Orders');?></a>
    <?php echo Sii::t('sii','and');?>
    <a style="position: relative;top: 0;right: 0;text-decoration: underline;" href="<?php echo url('item/');?>"><?php echo Sii::t('sii','My Items');?></a>.
    
</p>