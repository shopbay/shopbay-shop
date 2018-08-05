<div class="data-block">
    <div class="data-label"><strong><?php echo Sii::t('sii','Payment Method');?></strong></div>    
    <div>
        <?php echo $this->cart->getPaymentMethod()->renderConfirmationSnippet();?>
    </div>
</div>
  