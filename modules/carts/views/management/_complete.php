<div>
    <?php if (isset($status)&&$status!=Process::UNPAID): ?>
        <p>
            <?php echo Sii::t('sii','Your order {order_no} is {status}.',array('{status}'=>Process::getHtmlDisplayText($status),'{order_no}'=>CHtml::link($orderNo,$orderUrl,array('style'=>'color:red'))));?>
        </p>
    <?php elseif (!$paymentMethod->isOfflineMethod()): ?>
        <p>
            <?php echo Sii::t('sii','Your order {order_no} is confirmed and {status}.',array('{status}'=>Process::getHtmlDisplayText(Process::ORDERED),'{order_no}'=>CHtml::link($orderNo,$orderUrl,array('style'=>'color:red'))));?>
        </p>
        <p>
            <?php echo Sii::t('sii','Please keep this order number as your reference for future queries.');?>
        </p>
    <?php else: ?>
        <p>
            <?php echo Sii::t('sii','Your order number is {order_no} with {status} as you have chosen payment method <span style="color:red;">{method}</span>.',array('{status}'=>Process::getHtmlDisplayText(Process::UNPAID),'{order_no}'=>CHtml::link($orderNo,$orderUrl,array('style'=>'color:red')),'{method}'=>$paymentMethod->getMethodName(user()->getLocale())));?>
        </p>
        <div class="payment-method">
            <?php echo $paymentMethod->getDescription(user()->getLocale());?>
        </div>
    <?php endif; ?>    
</div>