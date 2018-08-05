<div class="data-block">
<?php if ($order->hasShippingMethodPickupOnly()):?>
    <div><strong><?php echo Sii::t('sii','Shipping Method');?></strong></div>
    <div class="data-element">
        <?php echo Shipping::model()->getMethodDesc(Shipping::METHOD_LOCAL_PICKUP);?>
    </div>
<?php else:?>
    <?php if ($order->address!=null):?>
    <div><strong><?php echo Sii::t('sii','Shipping Address');?></strong></div>
    <div>
        <div class="data-element"><?php echo $order->address->recipient;?></div>
        <?php if ($order->address->mobile!=null):?>
        <div class="data-element"><?php echo $order->address->mobile;?></div>
        <?php endif;?>
        <div class="data-element"><?php echo $order->address->address1;?></div>
        <?php if ($order->address->address2!=null):?>
        <div class="data-element"><?php echo $order->address->address2;?></div>
        <?php endif;?>
        <div class="data-element"><?php echo $order->address->postcode.', '.$order->address->city;?></div>
        <div class="data-element"><?php echo $order->address->state.', '.$order->address->country;?></div>
    </div>
    <?php endif;?>
</div>
<?php endif;?>
<?php if ($order->remarks!=null):?>
<div class="data-block">
    <div><strong><?php echo Sii::t('sii','Shipping Note');?></strong></div>
    <div class="data-element"><?php echo $order->remarks;?></div>
</div>
<?php endif;?>