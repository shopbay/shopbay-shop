<div class="data-block">
<?php $shippingAddress = $this->cart->getShippingAddress();?>
<?php if ($this->cart->hasShippingMethodPickupOnly()):?>
    <div class="data-label"><strong><?php echo Sii::t('sii','Shipping Method');?></strong></div>
    <div class="data-element">
        <?php echo Shipping::model()->getMethodDesc(Shipping::METHOD_LOCAL_PICKUP);?>
    </div>
<?php else:?>
    <div class="data-label"><strong><?php echo Sii::t('sii','Shipping Address');?></strong></div>
    <div class="data-elements">
        <div class="data-element"><?php echo $shippingAddress->recipient;?></div>
        <?php if ($shippingAddress->mobile!=null):?>
        <div class="data-element"><?php echo $shippingAddress->mobile;?></div>
        <?php endif;?>
        <div class="data-element"><?php echo $shippingAddress->address1;?></div>
        <?php if ($shippingAddress->address2!=null):?>
        <div class="data-element"><?php echo $shippingAddress->address2;?></div>
        <?php endif;?>
        <div class="data-element"><?php echo $shippingAddress->postcode.', '.$shippingAddress->city;?></div>
        <div class="data-element"><?php echo $shippingAddress->state.', '.$shippingAddress->country;?></div>
        <?php if ($shippingAddress->email!=null):?>
        <br>
        <div class="data-element"><?php echo $shippingAddress->email;?></div>
        <?php endif;?>
    </div>
<?php endif;?>
</div>
<?php if ($shippingAddress->note!=null):?>
<div class="data-block">
    <div class="data-label"><strong><?php echo Sii::t('sii','Shipping Note');?></strong></div>
    <div class="data-element"><?php echo $shippingAddress->note;?></div>
</div>
<?php endif;?>
