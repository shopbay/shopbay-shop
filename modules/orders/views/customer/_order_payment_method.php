<div class="data-block">
    <div><strong><?php echo Sii::t('sii','Payment Method');?></strong></div>    
    <div>
        <?php if ($order->getPayment()!=null): ?>
            <div class="data-element"><?php echo $order->getPayment()->payment_no;?></div>
        <?php endif;?>        
        <?php echo $this->renderPaymentConfirmationSnippet($order); ?>
    </div>
</div>