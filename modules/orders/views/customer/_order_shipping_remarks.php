<dt><?php echo Sii::t('sii','Shipping Method: {shipping_name}',array('{shipping_name}'=>$order->getShippingName(user()->getLocale(),$shipping)));?></dt>
    <ul>
        <?php if ($order->getShippingItemWeight($shipping)>0):?>
        <li>
            <?php echo Sii::t('sii','Subtotal Weight');?>
            <span id="weights_subtotal_<?php echo $shipping;?>">
                <?php echo $order->formatWeight($order->getShippingItemWeight($shipping));?>
            </span>        
        </li>
        <?php endif;?>
        <?php foreach ($this->getOrderShippingRemarks($order,$shipping) as $remark): ?>
        <li><?php echo $remark;?></li>
        <?php endforeach; ?>
    </ul>                        
</dd>