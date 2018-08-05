<dt>
    <?php echo Sii::t('sii','Shipping Method: {shipping_name}',array('{shipping_name}'=>$shopModel->parseLanguageValue($shippingData->shipping_name,user()->getLocale())));?>
    <?php echo $this->stooltipWidget(Helper::htmlList($this->cart->getShipping($shippingData->shipping_id)->getShippingRemarks(),array('class'=>'remarks')),array('position'=>SToolTip::POSITION_BOTTOM));?>
</dt>
<dd>
    <ul>
        <?php if ($shippingData->weight>0):?>
        <li>
            <?php echo Sii::t('sii','Subtotal Weight');?>
            <span id="weights_subtotal_<?php echo $shippingData->shipping_id;?>">
                <?php echo $shopModel->formatWeight($shippingData->weight);?>
            </span>        
        </li>
        <?php endif;?>
        <?php //foreach ($this->cart->getShipping($shippingData->shipping_id)->getShippingRemarks() as $remark): ?>
        <?php //echo CHtml::tag('li',array(),$remark);?>
        <?php //endforeach; ?>
    </ul>                        
</dd>
